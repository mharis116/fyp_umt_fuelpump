import warnings; warnings.filterwarnings("ignore")
import os, joblib, json
from pathlib import Path


import numpy  as np
import pandas as pd

import xgboost as xgb
import sklearn

# print(f"numpy {np.__version__} | pandas {pd.__version__} | "
#       f"sklearn {sklearn.__version__} | xgboost {xgb.__version__}")


# _model        = joblib.load("model_artefacts_v4/xgboost_model.pkl")
# _preprocessor = joblib.load("model_artefacts_v4/preprocessor.pkl")
# _label_enc    = joblib.load("model_artefacts_v4/label_encoder.pkl")

# with open("model_artefacts_v4/model_metadata.json") as f:
#     _meta = json.load(f)

BASE_DIR = Path(__file__).resolve().parent
ARTEFACTS_DIR = BASE_DIR / "model_artefacts_v4"

_model = joblib.load(ARTEFACTS_DIR / "xgboost_model.pkl")
_preprocessor = joblib.load(ARTEFACTS_DIR / "preprocessor.pkl")
_label_enc = joblib.load(ARTEFACTS_DIR / "label_encoder.pkl")

with open(ARTEFACTS_DIR / "model_metadata.json") as f:
    _meta = json.load(f)

REQUIRED_FEATURES = _meta["feature_names"]
TANK_CAPACITY     = 5.0


def predict_anomaly(record: dict, rolling_abs_variance_mean: float = None) -> dict:
    """
    Predict anomaly class for one dip record.

    Parameters
    ----------
    record : dict  — raw dip fields (see required_raw below)
    rolling_abs_variance_mean : float | None
        7-day rolling mean of abs_variance from your database.
        Pass None to skip sensor_jump_flag (flag will be 0).

    Returns
    -------
    dict: predicted_label, confidence, all_probabilities, warnings
    """
    warnings_list = []

    required_raw = [
        "last_dip_qty", "elapsed_hours", "temperature", "humidity",
        "total_sales_qty", "total_sales_count",
        "total_purchase_qty", "total_purchase_count",
        "qty", "variance", "abs_variance",
    ]
    missing = [k for k in required_raw if k not in record]
    if missing:
        raise ValueError(f"Missing fields: {missing}")

    qty = float(record["qty"])
    if qty < 0:
        warnings_list.append("qty negative — clamped to 0")
        record = {**record, "qty": 0.0}
    if qty > TANK_CAPACITY:
        warnings_list.append(f"qty {qty:.2f} exceeds tank capacity")

    computed_var = round(
        float(record["last_dip_qty"]) + float(record["total_purchase_qty"])
        - float(record["total_sales_qty"]) - float(record["qty"]), 2)
    if abs(computed_var - float(record["variance"])) > 0.05:
        warnings_list.append(
            f"Variance mismatch: provided={record['variance']}, computed={computed_var}")

    elapsed_h = float(record["elapsed_hours"])
    if elapsed_h > 48:
        warnings_list.append("elapsed_hours > 48 — long data gap detected")

    # ── reproduce all derived features ────────────────────────────────────────
    temp      = float(record["temperature"])
    hum       = float(record["humidity"])
    abs_var   = float(record["abs_variance"])
    purch_qty = float(record["total_purchase_qty"])
    sales_qty = float(record["total_sales_qty"])

    heat_dryness            = temp * (1 - hum / 100)
    purchase_variance_ratio = (abs_var / (purch_qty + 1e-9)) if purch_qty > 0 else 0.0
    variance_per_hour       = abs_var / (elapsed_h if elapsed_h > 0 else 1)
    temp_variance_interaction = temp * abs_var
    variance_to_sales_ratio   = abs_var / (sales_qty + 1e-9)
    evap_unexplained          = abs_var - (heat_dryness * 0.005)

    # sensor_jump_flag
    if rolling_abs_variance_mean is not None and rolling_abs_variance_mean > 0:
        sensor_jump_flag = int(abs_var > 3 * rolling_abs_variance_mean)
    else:
        sensor_jump_flag = 0

    long_gap_flag = int(elapsed_h > 48)

    row = {
        "last_dip_qty"              : float(record["last_dip_qty"]),
        "elapsed_hours"             : elapsed_h,
        "temperature"               : temp,
        "humidity"                  : hum,
        "total_sales_qty"           : sales_qty,
        "total_sales_count"         : int(record["total_sales_count"]),
        "total_purchase_qty"        : purch_qty,
        "total_purchase_count"      : int(record["total_purchase_count"]),
        "qty"                       : float(record["qty"]),
        "variance"                  : float(record["variance"]),
        "abs_variance"              : abs_var,
        "heat_dryness"              : heat_dryness,
        "purchase_variance_ratio"   : purchase_variance_ratio,
        "variance_per_hour"         : variance_per_hour,
        "temp_variance_interaction" : temp_variance_interaction,
        "variance_to_sales_ratio"   : variance_to_sales_ratio,
        "evap_unexplained"          : evap_unexplained,
        "sensor_jump_flag"          : sensor_jump_flag,
        "long_gap_flag"             : long_gap_flag,
    }

    X_input = pd.DataFrame([row])[REQUIRED_FEATURES]
    X_proc  = _preprocessor.transform(X_input)
    proba   = _model.predict_proba(X_proc)[0]
    pred_idx = int(np.argmax(proba))

    return {
        "predicted_label"   : _label_enc.classes_[pred_idx],
        "confidence"        : round(float(proba[pred_idx]), 4),
        "all_probabilities" : {cls: round(float(p), 4)
                               for cls, p in zip(_label_enc.classes_, proba)},
        "warnings"          : warnings_list,
    }

if __name__ == '__main__':
    # ── smoke tests (all 4 classes) ───────────────────────────────────────────────
    tests = [
        ("theft",            {"last_dip_qty":3.5,"elapsed_hours":24.1,"temperature":35.0,
                            "humidity":45.0,"total_sales_qty":0.2,"total_sales_count":2,
                            "total_purchase_qty":0.0,"total_purchase_count":0,
                            "qty":0.8,"variance":2.5,"abs_variance":2.5}),
        ("evaporation",      {"last_dip_qty":3.0,"elapsed_hours":24.0,"temperature":43.0,
                            "humidity":25.0,"total_sales_qty":0.5,"total_sales_count":4,
                            "total_purchase_qty":0.0,"total_purchase_count":0,
                            "qty":2.34,"variance":0.16,"abs_variance":0.16}),
        ("normal",           {"last_dip_qty":3.0,"elapsed_hours":24.0,"temperature":15.0,
                            "humidity":60.0,"total_sales_qty":0.3,"total_sales_count":3,
                            "total_purchase_qty":0.0,"total_purchase_count":0,
                            "qty":2.70,"variance":0.0,"abs_variance":0.0}),
        ("missing_purchase", {"last_dip_qty":2.0,"elapsed_hours":24.0,"temperature":20.0,
                            "humidity":55.0,"total_sales_qty":0.2,"total_sales_count":2,
                            "total_purchase_qty":1.5,"total_purchase_count":1,
                            "qty":1.80,"variance":1.5,"abs_variance":1.5}),
    ]

    print(f"{'Expected':20s}  {'Predicted':20s}  Conf    Match")
    print("-" * 60)
    all_pass = True
    for expected, rec in tests:
        res   = predict_anomaly(rec, rolling_abs_variance_mean=0.05)
        match = "✅" if res["predicted_label"] == expected else "❌"
        if match == "❌": all_pass = False
        print(f"{expected:20s}  {res['predicted_label']:20s}  "
            f"{res['confidence']:.4f}  {match}")
    print(f"\nAll smoke tests passed: {all_pass}")
