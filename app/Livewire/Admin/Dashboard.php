<?php

namespace App\Livewire\Admin;


use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $total_revenue = 0;
    public $revenueDeviation = 0;
    public $revenuePrevious = 0;
    public $sales_count = 0;
    public $stock_value = 0;

    public $receivables = 0;
    public $overdue_invoices = 0;
    public $loss_summary = 0;

    public $instance;
    public $month;
    public $previousMonth;


    protected $listeners = [
        'done' => 'render'
    ];

    function mount()
    {

        $this->instance = Carbon::now();
        $this->month = $this->instance->format('Y-m');
        $this->total_revenue = $this->getTotalRevenue($this->instance->format('Y-m'));
        $this->revenuePrevious = $this->getTotalRevenue($this->instance->subMonth()->format('Y-m'));

        if ($this->total_revenue  !== 0) {
            $this->revenueDeviation = ($this->total_revenue - $this->revenuePrevious) / $this->total_revenue;
        }
        $this->dispatch('done');
    }

    function updatedMonth()
    {
        $this->instance = Carbon::parse($this->month);
        $this->total_revenue = $this->getTotalRevenue($this->instance->format('Y-m'));
        $this->revenuePrevious = $this->getTotalRevenue($this->instance->subMonth()->format('Y-m'));

        if ($this->total_revenue  !== 0) {
            $this->revenueDeviation = ($this->total_revenue - $this->revenuePrevious) / $this->total_revenue;
        }
    }


    function getSalesChart($date)
    {
        // Generate a period of the last 5 months
        // $date = $this->instance->toDateString();
        $end = Carbon::parse($date);
        $start = Carbon::parse($date)->subMonthsNoOverflow(6);
        $period = CarbonPeriod::since($start)->months(1)->until($end);

        // Map the period to an array of month names or desired format

        $months = [];
        foreach ($period as $key => $month) {
            array_push($months, $month->format('Y-m'));
        }
        $lineChartModel = LivewireCharts::multiLineChartModel();

        // dd($months);
        foreach ($months as $key => $month) {
            $lineChartModel->addSeriesPoint("Sales ",  Carbon::parse($month)->format('F Y'), $this->getTotalRevenue($month));
            $lineChartModel->addSeriesPoint("Purchases ", Carbon::parse($month)->format('F Y'),  $this->getTotalPurchases($month));
        }
        $lineChartModel->setAnimated('ease-in');
        $lineChartModel->setSmoothCurve();
        $lineChartModel->setColors(["#336699", "#f29f67"]);
        $lineChartModel->setDataLabelsEnabled(true);
        // $lineChartModel


        $lineChartModel->setJsonConfig([
            'tooltip.y.formatter' => '(val) => `Rp ${val.toLocaleString()}`',
            'dataLabels.formatter' => '(val) => `Rp ${val.toLocaleString()}`',
            'yaxis.labels.formatter' => '(val) => `Rp ${val.toLocaleString()}`',
            // 'chart'=>"area"
        ]);

        return $lineChartModel;
    }

    function getSalesTotal($month)
    {
        if (!isset($month)) {
            return 0;
        }

        return DB::table('sales')
            ->join('product_sale', 'sales.id', '=', 'product_sale.sale_id')
            ->whereRaw('DATE_FORMAT(sales.created_at, "%Y-%m") = ?', [$month])
            ->sum(DB::raw('product_sale.quantity * product_sale.unit_price'));
    }

    function getTotalRevenue($month)
    {
        if (!isset($month)) {
            return 0;
        }

        return DB::table('sales')
            ->join('product_sale', 'sales.id', '=', 'product_sale.sale_id')
            ->whereRaw('DATE_FORMAT(sales.created_at, "%Y-%m") = ?', [$month])
            ->sum(DB::raw('product_sale.quantity * product_sale.unit_price'));
    }

    function getTotalPurchases($month)
    {
        if (!isset($month)) {
            return 0;
        }

        return DB::table('purchases')
            ->join('product_purchase', 'purchases.id', '=', 'product_purchase.purchase_id')
            ->whereRaw('DATE_FORMAT(purchases.created_at, "%Y-%m") = ?', [$month])
            ->sum(DB::raw('product_purchase.quantity * product_purchase.unit_price'));
    }

    function getTotalSalesPayments($month)
    {
        if (!isset($month)) {
            return 0;
        }

        return DB::table('sales_payments')
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$month])
            ->sum('amount');
    }

    function getTotalPurchasePayments($month)
    {
        if (!isset($month)) {
            return 0;
        }

        return DB::table('purchase_payments')
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$month])
            ->sum('amount');
    }

    function downloadAccountSummary($month)
    {
        $pdf = Pdf::loadView('pdf.account-summary', [
            'date' => Carbon::parse($month)->format('F, Y'),
            'total_sales' => $this->getTotalRevenue($month),
            'total_sales_payments' => $this->getTotalSalesPayments($month),
            'total_purchases' => $this->getTotalPurchases($month),
            'total_purchase_payments' => $this->getTotalPurchasePayments($month),
        ]);
        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, Carbon::parse($month)->endOfMonth()->format('m-Y') . ' - Accounts Summary Statement.pdf');
    }

    public function render()
    {

        $lineChartModel = $this->getSalesChart($this->month);

        return view('livewire.admin.dashboard', [
            'lineChartModel' => $lineChartModel
        ]);
    }
}
