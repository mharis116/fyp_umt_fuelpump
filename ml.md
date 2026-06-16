I’ll go line by line and explain what each feature *really means in your fuel system context*, including **why “sensor jump” exists**.

---

# 🔷 BASE IDENTIFIERS

### `pro_id`

Which fuel tank / product this row belongs to.
👉 Needed to separate each tank’s behavior (very important for ML).

---

# 🔷 TIME FEATURE

### `elapsed_hours`

```sql
time difference between current dip and previous dip
```

👉 Meaning:

* how long system “behaved” before next reading
* helps distinguish:

  * fast theft (minutes/hours)
  * slow leak (many hours/days)
  * normal stable storage

---

# 🔷 SENSOR STATE FEATURES

### `current_dip_qty`

```sql
qty
```

👉 Current measured fuel level

---

### `last_dip_qty`

👉 Previous corrected dip value

Why important:

* gives **baseline reference**
* ML learns trend direction (stable / dropping / rising)

---

### `change_in_qty`

👉 Manual/automatic correction applied during dip

Why important:

* shows **operator intervention**
* helps detect:

  * recalibration
  * manual stock adjustment
  * sensor correction events

---

# 🔷 ENVIRONMENT FEATURES

### `temperature`

👉 Outside temperature

Why important:

* fuel expands in heat → level increases
* fuel contracts in cold → level decreases

👉 Helps ML avoid false theft alerts.

---

### `humidity`

👉 Environmental moisture condition

Why important:

* weak but useful signal for:

  * condensation effects
  * weather correlation with evaporation

---

# 🔷 BUSINESS ACTIVITY FEATURES

### `total_sales_qty`

👉 Fuel sold between last dip → current dip

Meaning:

* real demand drain from tank

---

### `total_sales_count`

👉 Number of transactions

Meaning:

* many small sales vs few large sales
* helps distinguish:

  * normal usage patterns
  * abnormal spikes

---

### `total_purchase_qty`

👉 Fuel added to tank

Meaning:

* refilling events

---

### `total_purchase_count`

👉 Number of refill transactions

Meaning:

* frequent small refills vs big tanker refill

---

# 🔷 CORE ANOMALY FEATURES

### `variance`

```text
(expected stock - actual stock)
```

Where:

```
expected = last_dip + purchases - sales
```

👉 Meaning:

* THIS is your main anomaly signal

Interpretation:

* positive → more fuel than expected
* negative → missing fuel

---

### `abs_variance`

👉 absolute value of variance

Why important:

* ML needs magnitude without direction
* helps detect “how bad” anomaly is

Example:

* -5 liters = same anomaly strength as +5 liters

---

# 🔷 DERIVED FEATURES

### `variance_rate`

```text
variance / time
```

👉 Meaning:

* how fast anomaly is happening

Helps detect:

* sudden theft → high rate
* slow leak → low rate

---

### `direction`

```text
SIGN(variance)
```

Values:

* `-1` → fuel loss (most important → theft/leak)
* `+1` → unexpected gain (sensor error / overfill)
* `0` → normal

👉 This is classification-friendly signal

---

### `expected_change`

```text
total_purchase_qty - total_sales_qty
```

👉 Meaning:
net business movement

* positive → tank should increase
* negative → tank should decrease

Helps ML learn:

> “what SHOULD have happened”

---

### `activity_intensity`

```text
sales + purchases
```

👉 Meaning:
how active the station was

Why useful:

* busy station = more natural fluctuation
* idle station = suspicious even small changes

---

### `long_gap_flag`

```text
time gap > 48 hours
```

👉 Meaning:
detects missing data periods

Why important:

* large gaps often cause fake anomalies
* batch corrections may happen after downtime
* prevents ML confusion

---

# 🔷 SENSOR-SPECIFIC FEATURE

## ⚠️ `sensor_jump_flag` (important)

```text
ABS(qty - previous qty) > threshold
```

👉 Meaning:
detects sudden unrealistic jumps in sensor reading

---

### Why this is needed:

Fuel sensors are NOT perfect:

* calibration drift
* electrical noise
* stuck readings
* sudden spikes due to vibration
* reset events

---

### Example:

| time  | qty   |
| ----- | ----- |
| 10:00 | 100   |
| 10:05 | 101   |
| 10:10 | 250 ❌ |

That jump is NOT real fuel change → it's sensor fault

---

### So sensor_jump_flag helps ML:

* separate **real theft vs sensor glitch**
* avoid false alarms

---

# 🔷 LABEL

### `true_label`

👉 Ground truth classification

Example:

* normal
* theft
* leak
* sensor_error

This is what ML learns from.

---

# 🧠 FINAL SUMMARY (VERY IMPORTANT)

Your model is learning 4 things:

### 1. Physics of fuel system

* variance
* temperature effects

### 2. Business flow

* sales/purchases

### 3. Time behavior

* elapsed_hours
* long_gap_flag

### 4. Sensor reliability

* sensor_jump_flag
* change_in_qty

---

# ⚡ SIMPLE WAY TO THINK

Each row answers:

> “Given everything that happened since last dip, is this tank behavior normal or suspicious?”

---

If you want next step, I can help you:

✔ convert this into **ML training pipeline (Python code)**
✔ choose best model (Isolation Forest vs XGBoost)
✔ or design real-time fraud detection system (MQTT → API → ML → alert)
