<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cust_ledger;
use Illuminate\Support\Facades\DB;
use App\sup_ledger;
use App\sales;
use App\Expense;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'reports';

        $this->ignored_permission_methods = [
            'ai_ask'
        ];

        $this->permission_methods = [
            'profit' => [
                'module_permission_type_code' => 'profit-loss-report',
            ],
            'profitfilter' => [
                'module_permission_type_code' => 'profit-loss-report',
            ],

            'credit' => [
                'module_permission_type_code' => 'credit-report',
            ],

            'dailysale' => [
                'module_permission_type_code' => 'daily-sales-report',
            ],
            'dailysaleitem' => [
                'module_permission_type_code' => 'daily-sales-report',
            ],
            'dailysalefilter' => [
                'module_permission_type_code' => 'daily-sales-report',
            ],

            'expense' => [
                'module_permission_type_code' => 'expense-report',
            ],
            'expensefilter' => [
                'module_permission_type_code' => 'expense-report',
            ],

            'price' => [
                'module_permission_type_code' => 'fuel-price-report',
            ],
            'pricefilter' => [
                'module_permission_type_code' => 'fuel-price-report',
            ],
        ];
    }

    //region credit
    public function credit($return_type='view'){
        $cl = cust_ledger::where('isdeleted',0)->selectRaw('sum(dr) as credit,sum(adjustment) as adj')->first();
        $sl = sup_ledger::where('isdeleted',0)->selectRaw('sum(dr) as credit,sum(adjustment) as adj')->first();

        if($return_type=='json'){
            return $this->formatCreditData($cl, $sl);
        }
        return view('report.ledger.credit')->with('cl',$cl)->with('sl',$sl);
    }

    private function formatCreditData($cl, $sl)
    {
        $customerTotal = ($cl->credit ?? 0) + ($cl->adj ?? 0);
        $supplierTotal = ($sl->credit ?? 0) + ($sl->adj ?? 0);

        return [
            'title' => 'Credit Report',

            'summary' => [
                'customer' => [
                    'label' => 'Customer',
                    'amount' => $customerTotal,
                    'formatted_amount' => 'Rs.' . $customerTotal,
                    'payment_type' => $customerTotal > 0 ? 'Receiveable' : 'Payable',
                    'color' => 'green',
                    'route' => route('custledger.index', [
                        'test' => 1,
                        'total' => $customerTotal
                    ])
                ],

                'supplier' => [
                    'label' => 'Supplier',
                    'amount' => $supplierTotal,
                    'formatted_amount' => 'Rs.' . $supplierTotal,
                    'payment_type' => $supplierTotal > 0 ? 'Payable' : 'Receiveable',
                    'color' => 'purple',
                    'route' => route('supledger.index', [
                        'test' => 1,
                        'total' => $supplierTotal
                    ])
                ],
            ],

            'chart' => [
                'type' => 'doughnut',
                'labels' => ['Customer', 'Supplier'],
                'datasets' => [
                    [
                        'label' => 'Credit Report',
                        'data' => [$customerTotal, $supplierTotal],
                        'backgroundColor' => ['green', 'purple']
                    ]
                ]
            ]
        ];
    }

    //region daily sale
    public function dailysale($return_type='view'){
        $sale = Sales::where('isdeleted',0)->groupBy('date')
        ->selectRaw('sum(total_qty) as qty ,sum(retail_amount) as rm,sum(adjustment) as adj,date')
        ->get();
        

        if($return_type=="json"){
            return $this->formatDailySaleData($sale);
        }

        // dd($sale);
        return view('report.sale.dailysale', ['report_identifier'=>'dailysale'])->with('sale',$sale);
    }

    public function dailysaleitem($date){
        $sale = Sales::where('sales.isdeleted',0)
        ->join('customers','customers.id','sales.customer_id')
        ->where('sales.date',$date)
        ->select('sales.total_qty as qty' ,'sales.retail_amount as rm','sales.adjustment','sales.date','sales.invoice_no','customers.name','sales.id')
        ->get();
        return view('report.sale.dailysaleitem')->with('sale',$sale);
    }

    public function dailysalefilter(Request $request, $return_type='view'){
        $date = $request->date;
        if(!empty($date) and !$request->clear){
            $date = explode(" to ",$date);
            $to = $date[1];
            $from = $date[0];
            // $datecol = 'date';
            $list = [$from,$to];
            $where = 'whereBetween';
            $sale = Sales::where('isdeleted',0)->groupBy('date')
            ->selectRaw('sum(total_qty) as qty ,sum(retail_amount) as rm,sum(adjustment) as adj,date')
            ->$where('date',$list)
            ->get();

            if($return_type=="json"){
                return $this->formatDailySaleData($sale, $from, $to);
            }

            return view('report.sale.dailysale',['report_identifier'=>'dailysalefilter', 'filters' => $request->all()])
            ->with('from',$from)
            ->with('to',$to)
            ->with('sale',$sale);
        }else if($request->clear){
            return redirect(route('report.sale.dailysale'));
        }

    }

    private function formatDailySaleData($sale, $from = null, $to = null)
    {
        return [
            'title' => 'Daily Sales Report',

            'table' => $sale->map(function ($s) {
                return [
                    'date'        => $s->date,
                    'sale_amount' => (float) $s->rm,
                    'adjustment'  => (float) ($s->adj ?? 0),
                    'total_sale'  => (float) ($s->rm + ($s->adj ?? 0)),
                    'quantity'    => (float) $s->qty,
                ];
            }),

            // =========================
            // 🧠 AI-UNDERSTOOD CHARTS
            // =========================
            'charts' => [
                [
                    'title' => 'Daily Fuel Sale Quantity',
                    'type'  => 'area',
                    'x_axis_label' => 'Date',
                    'y_axis_label' => 'Liters Sold',

                    'labels' => $sale->map(fn($s) => $s->date)->values(),

                    'datasets' => [
                        [
                            'label' => 'Fuel Quantity Sold',
                            'data'  => $sale->map(fn($s) => (float) $s->qty)->values(),
                            'color' => '#727cf5'
                        ]
                    ]
                ],

                [
                    'title' => 'Daily Sales Revenue',
                    'type'  => 'area',
                    'x_axis_label' => 'Date',
                    'y_axis_label' => 'PKR',

                    'labels' => $sale->map(fn($s) => $s->date)->values(),

                    'datasets' => [
                        [
                            'label' => 'Total Cash (Sale + Adjustment)',
                            'data'  => $sale->map(fn($s) => (float) ($s->rm + ($s->adj ?? 0)))->values(),
                            'color' => '#10b981'
                        ]
                    ]
                ]
            ],

            // =========================
            // SUMMARY
            // =========================
            'summary' => [
                'total_quantity' => $sale->sum('qty'),
                'total_sale_amount' => $sale->sum('rm'),
                'total_adjustment' => $sale->sum('adj'),
                'total_final' => $sale->sum(fn($s) => $s->rm + ($s->adj ?? 0)),
                'records' => $sale->count(),
                'date_from' => $from,
                'date_to' => $to,
            ],
        ];
    }

    //region profit report
    public function profit($return_type='view'){
        $sale = Sales::where('isdeleted',0)->selectRaw('sum(retail_amount) as rtm,sum(cost_amount) as ctm,sum(adjustment) as adj')->first();
        $exp = Expense::where('isdeleted',0)->selectRaw('sum(amount) as exp')->first();
        $rtm = $sale->rtm + $sale->adj;
        $groce = $rtm - $sale->ctm;
        $net = $groce - $exp->exp;

        $salemy = Sales::where('sales.isdeleted',0)->groupBy(DB::Raw('date_format(sales.date,"%m-%Y")'))
        ->selectRaw('sum(sales.retail_amount) as rtm,sum(sales.cost_amount) as ctm,sum(sales.adjustment) as adj,sum(sales.retail_amount + sales.adjustment - sales.cost_amount) as gp,date_format(sales.date,"%m-%Y") as date')->get();


        if($return_type=="json"){
            return $this->formatProfitData($sale, $salemy, $exp->exp, $from ?? null, $to ?? null);
        }

        return view('report.sale.profit',['report_identifier'=>'profit'])
        ->with('rtm',$rtm)
        ->with('ctm',$sale->ctm)
        ->with('salemy',$salemy)
        ->with('gp',$groce)
        ->with('np',$net)
        ->with('exp',$exp->exp);
    }

    public function profitfilter(Request $request, $return_type='view'){
        $date = $request->date;
        $sale = Sales::where('isdeleted',0)->selectRaw('sum(retail_amount) as rtm,sum(cost_amount) as ctm,sum(adjustment) as adj')->first();
        $exp = Expense::where('isdeleted',0)->selectRaw('sum(amount) as exp')->first();
        $rtm = $sale->rtm + $sale->adj;
        $groce = $rtm - $sale->ctm;
        $net = $groce - $exp->exp;
        // dd($net);
        if(!empty($date) and !$request->clear){
            $date = explode(" to ",$date);
            $to = $date[1];
            $from = $date[0];
            $datecol = 'date';
            $list = [$from,$to];
            $where = 'whereBetween';
            $salemy = Sales::where('sales.isdeleted',0)
            ->where('expenses.isdeleted',0)
            ->groupBy(DB::Raw('date_format(sales.date,"%m-%Y")'))
            ->groupBy(DB::Raw('date_format(expenses.date,"%m-%Y")'))
            ->leftJoin('expenses',DB::Raw('date_format(expenses.date,"%m-%Y")'),DB::Raw('date_format(sales.date,"%m-%Y")'))
            ->selectRaw('sum(sales.retail_amount) as rtm,sum(sales.cost_amount) as ctm, sum(sales.adjustment) as adj, date_format(sales.date,"%m-%Y") as date,sum(expenses.amount) as exp')
            ->$where('sales.date',$list)
            ->get();

            if($return_type=="json"){
                return $this->formatProfitData($sale, $salemy, $exp->exp, $from ?? null, $to ?? null);
            }

            // dd($salemy);
            return view('report.sale.profit',['report_identifier'=>'profitfilter', 'filters' => $request->all()])
            ->with('from',$from)
            ->with('to',$to)
            ->with('rtm',$rtm)
            ->with('ctm',$sale->ctm)
            ->with('salemy',$salemy)
            ->with('gp',$groce)
            ->with('np',$net)
            ->with('exp',$exp->exp);
        }else if($request->clear){
            return redirect(route('report.sale.profit'));
        }

    }

    private function formatProfitData($sale, $salemy = null, $exp = 0, $from = null, $to = null)
    {
        // ===== TOP SUMMARY (same as first table in UI)
        $rtm = ($sale->rtm ?? 0) + ($sale->adj ?? 0);
        $ctm = $sale->ctm ?? 0;
        $expense = $exp ?? 0;

        $gp = $rtm - $ctm;
        $np = $gp - $expense;

        // ===== MONTHLY DATA (same as bottom table + chart)
        $rows = [];
        $dates = [];
        $gpData = [];

        if ($salemy) {
            foreach ($salemy as $s) {
                $rtm_m = ($s->rtm ?? 0) + ($s->adj ?? 0);
                $gp_m  = $rtm_m - ($s->ctm ?? 0);

                $rows[] = [
                    'date' => $s->date,
                    'invest' => (float) $s->ctm,
                    'retail' => (float) $s->rtm,
                    'adjustment' => (float) ($s->adj ?? 0),
                    'groce_profit' => (float) $gp_m,
                    'groce_profit_percent' => $s->rtm > 0 ? round(($gp_m / $s->rtm) * 100, 2) : 0,
                ];

                $dates[] = $s->date;
                $gpData[] = (float) $gp_m;
            }
        }

        return [
            'title' => 'Profit & Loss Report',

            // ===== SAME AS TOP CARD TABLE
            'summary' => [
                'invest' => (float) $ctm,
                'retail' => (float) $rtm,
                'expense' => (float) $expense,
                'gross_profit' => $gp > 0 ? (float) $gp : 0,
                'gross_loss' => $gp < 0 ? (float) abs($gp) : 0,
                'net_profit' => $np > 0 ? (float) $np : 0,
                'net_loss' => $np < 0 ? (float) abs($np) : 0,
                'date_from' => $from,
                'date_to' => $to,
            ],

            'chart' => [
                'title' => 'Date Wise Gross Profit & Loss',
                'labels' => $dates,
                'data' => $gpData
            ],

            'table' => $rows
        ];
    }

    //region expense report
    public function expense($return_type='view'){
        $exp = Expense::where('isdeleted',0)->selectRaw('date_format(date,"%m-%Y") as date,sum(amount) as exp')->groupBy(DB::Raw('date_format(date,"%m-%Y")'))->get();
        if($return_type=="json"){
            return $this->formatExpenseData($exp);
        }
        return view('report.expense', ['report_identifier'=>'expense'])->with('exp',$exp);
    }
    public function expensefilter(Request $request, $return_type='view'){
        $date = $request->date;
        if(!empty($date) and !$request->clear){
            $date = explode(" to ",$date);
            $to = $date[1];
            $from = $date[0];
            $datecol = DB::Raw('date_format(date,"%m-%Y")');
            $list = [date_format(date_create($from),'m-Y'),date_format(date_create($to),'m-Y')];
            $where = 'whereBetween';
            $exp = Expense::where('isdeleted',0)->selectRaw('date_format(date,"%m-%Y") as date,sum(amount) as exp')
            ->$where($datecol,$list)
            ->groupBy(DB::Raw('date_format(date,"%m-%Y")'))->get();

            if($return_type=="json"){
                return $this->formatExpenseData($exp, $from, $to);
            }
            return view('report.expense', ['report_identifier'=>'expensefilter', 'filters' => $request->all()])
            ->with('from',$from)
            ->with('to',$to)
            ->with('exp',$exp);
        }else if($request->clear){
            return redirect(route('report.expense'));
        }
    }

    public function expenseitem($date){
        // $expt = Expense::where('isdeleted',0)->selectRaw('sum(amount) as exp')->where(DB::Raw('date_format(date,"%m-%Y")'),$date)->first();
        $exp = Expense::where('expenses.isdeleted',0)->where(DB::Raw('date_format(expenses.date,"%m-%Y")'),$date)->join('exp_types','exp_types.id','expenses.exp_type_id')->get();
        return view('report.dailyexpitem')->with('exp',$exp);
    }

    private function formatExpenseData($exp, $from = null, $to = null)
    {
        $labels = [];
        $amounts = [];

        foreach ($exp as $e) {
            $labels[] = $e->date;
            $amounts[] = (float) $e->exp;
        }

        return [
            'title' => 'Monthly Expense Report',

            'filters' => [
                'from' => $from,
                'to'   => $to,
            ],

            // 🔥 Chart exactly like UI
            'chart' => [
                'title' => 'Monthly Expense Report', // IMPORTANT for AI context
                'type'  => 'area',

                'x' => $labels,

                'series' => [
                    [
                        'name' => 'Amount',
                        'data' => $amounts
                    ]
                ]
            ],

            // 🔥 Table same as UI
            'table' => $exp->map(function ($e) {
                return [
                    'date'   => $e->date,
                    'amount' => (float) $e->exp,
                ];
            })
        ];
    }

    //region price report
    public function price($return_type='view'){

        $prices = DB::table('prices')->join('products','products.id','prices.pro_id')->select('products.name','prices.*',Db::Raw('date_format(prices.date,"%Y-%m-%d") as date'))->get();
        if ($return_type === 'json') {
            return $this->formatPriceData($prices);
        }
        return view('report.price',['report_identifier'=>'price'])->with('price',$prices);
    }

    public function pricefilter(Request $request, $return_type='view'){
        $date = $request->date;
        if(!empty($date) and !$request->clear){
            $date = explode(" to ",$date);
            $to = $date[1];
            $from = $date[0];
            $datecol = DB::Raw('date_format(date,"%Y-%m-%d")');
            $list = [date_format(date_create($from),'Y-m-d'),date_format(date_create($to),'Y-m-d')];
            $where = 'whereBetween';
            $prices = DB::table('prices')->join('products','products.id','prices.pro_id')->select('products.name','prices.*',Db::Raw('date_format(prices.date,"%Y-%m-%d") as date'))->$where($datecol,$list)->get();

            if ($return_type === 'json') {
                return $this->formatPriceData($prices, $from, $to);
            }

            return view('report.price', ['report_identifier'=>'pricefilter', 'filters' => $request->all()])
            ->with('from',$from)
            ->with('to',$to)
            ->with('price',$prices);
        }else if($request->clear){
            return redirect(route('report.price'));
        }
    }

    private function formatPriceData($prices, $from = null, $to = null)
    {
        return [
            'title' => 'Fuel Price Report',

            'filters' => [
                'from' => $from,
                'to'   => $to,
            ],

            'table' => $prices->map(function ($p) {
                return [
                    'date'   => $p->date,
                    'product'=> $p->name,
                    'purchase_price'   => (float) $p->cost_price,
                    'sale_price' => (float) $p->retail_price,
                    'margin' => (float) ($p->retail_price - $p->cost_price),
                ];
            })
        ];
    }


    //region ai

    public function ai_ask(Request $request){
        $validated =$request->validate([
            'question' => 'required',
            'report' => 'required',
            'filters' => 'nullable',
        ]);

        $filters = $validated['filters'] ?? null;
        // dd(json_decode($filters));
        if( $filters){
            $request->merge(json_decode($filters, true));

            $report_json = $this->{$validated['report']}(request:$request, return_type:"json");
        }else{
            $report_json = $this->{$validated['report']}(return_type:"json");
        }


        $question = $validated['question'];
        // dd(env("AI_AGENT_URL"));
        try {
            $response = Http::timeout(30)->post(env("AI_AGENT_URL").'/chat_json', [
                'query' => $question,
                'json_data'   => json_encode($report_json),
            ]);

            if ($response->failed()) {
                return response()->json([
                    'answer' => 'AI service error. Please try again.',
                    'error' => $response
                ], 500);
            }

            return response()->json([
                'answer' => $response->json()['response'] ?? 'No response from AI.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'answer' => 'Server error: ' . $e->getMessage()
            ], 500);
        }

    }
}
