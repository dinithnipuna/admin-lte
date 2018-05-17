<?php 
namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use DB;
use App\Purchase;
use Charts;
use App\Sale;
use App\Product;

class ReportController extends Controller {

	public function __construct()
    {
        $this->middleware('auth');
 
    }
 
    /**
     * Display a listing of the user.
     *
     * @return Response
     */
    public function index()
    {
            return view('reports.index');
    }

    public function getSales()
    {     
        $sales =  Sale::groupBy('created_at')
                        ->selectRaw('*, sum(total) as sale')
                        ->get();

         $chart = Charts::database($sales,'bar', 'highcharts')
                        ->setTitle('Monthly Sales Report')
                        ->setElementLabel("Sales")
                        ->setResponsive(true)
                        ->setSumOfField('sale')
                        ->groupByMonth();

        return view('reports.sales')->with('chart',$chart);
    }


    public function getPurchases2()
    {
        $purchases =  Purchase::groupBy('created_at')
                        ->selectRaw('*, sum(total) as purchases')
                        ->get();

         $chart = Charts::database($purchases, 'bar', 'highcharts')
                            ->setTitle('Monthly Purchases Report')
                            ->setElementLabel("Purchases")
                            ->setResponsive(true)
                            ->setSumOfField('purchases')
                            ->groupByMonth();
                            
        return view('reports.purchases')->with('chart',$chart);
    }

    public function getPurchases()
    {                            
        return view('reports.purchases');
    }

    public function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

    public function post()
    {
        
        $database = \Config::get('database.connections.mysql');
        $output = public_path() . '/report/'.time().'_codelution';
        
        $ext = "pdf";

        \JasperPHP::process(
            public_path() . '/report/codelution.jasper', 
            $output, 
            array($ext),
            array(),
            $database,
            false,
            false
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.time().'_codelution.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext); // deletes the temporary file
        
        return Redirect::to('/reporting');
    }


    public function getTrialBalance()
    {                   
        return view('reports.trial_balance');
    }

    public function postTrialBalance(Request $request)
    {                   
        $report = '';
        $year = $request->year;
        $month = $request->month;
        $date = $request->trans_date;

         if($request->term =="annual"){  
                $sales = Sale::whereYear('created_at','=', $year)->get();
                    
         }else if($request->term =="monthly"){
                $sales = Sale::whereYear('created_at','=', $year)
                          ->whereMonth('created_at','=' ,$month)->get();
         }else{
                $sales = Sale::whereDate('created_at','=', $date)->get();
         }
        

    if($sales->count() > 0){
        $report .='<div class="row"><div class="col-md-12"><div class="box box-widget"><div class="box-body">';

        if($request->term =="annual"){  
        $report .='<h3 class="text-center">Sales Report For Year ' .$year.'</h3>';
        }else if($request->term =="monthly") {
        $report .='<h3 class="text-center">Sales Report For ' .date("F", strtotime(date("Y")."-".$month."-01")). ' ' .$year.'</h3>';
        }else{
          $report .='<h3 class="text-center">Sales Report For ' .$date. '</h3>';  
        }

        $report .='<table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Date</th>
                  <th>Invoice No</th>
                  <th style="text-align:right">Amount</th>
                </tr>';

        $total_amount=0;
        $row =0;   

        foreach($sales as $sale){
            $row++;
            $report .='<tr>
                  <td>'.$row.'</td> 
                  <td>'.$sale->created_at.'</td>
                  <td>SN'.str_pad($sale->id , 7, '0', STR_PAD_LEFT).'</td>
                  <td align="right">'.number_format($sale->total, 2, '.', ',').'</td>
                  </tr>';
                  $total_amount +=$sale->total;
        }
        $report .='<tr>
            <th colspan="3" style="text-align:right">Total Amount</th>
            <th style="text-align:right">'.number_format($total_amount, 2, '.', ',').'</th>
        </tr>';
         $report .='</table>';

        $report .='  <div class="row no-print">
        <div class="col-xs-12">
          <button type="button" class="btn btn-success pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print
          </button></div></div>';

    }else{
         $report = '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Not enough data to genarate report.
              </div>';
    }
         $report .=' </div>
    </div>
    </div>
    </div>';
        return $report;
    }

    public function postPurchases(Request $request)
    {                   
        $report = '';
        $year = $request->year;
        $month = $request->month;
        $date = $request->trans_date;

         if($request->term =="annual"){  
                $purchases = Purchase::whereYear('po_at','=', $year)->get();
                    
         }else if($request->term =="monthly"){
                $purchases = Purchase::whereYear('po_at','=', $year)
                          ->whereMonth('po_at','=' ,$month)->get();
         }else{
                $purchases = Purchase::whereDate('po_at','=', $date)->get();
         }
        

    if($purchases->count() > 0){
        $report .='<div class="row"><div class="col-md-12"><div class="box box-widget"><div class="box-body">';

        if($request->term =="annual"){  
        $report .='<h3 class="text-center">Purchase Report For Year ' .$year.'</h3>';
        }else if($request->term =="monthly") {
        $report .='<h3 class="text-center">Purchase Report For ' .date("F", strtotime(date("Y")."-".$month."-01")). ' ' .$year.'</h3>';
        }else{
          $report .='<h3 class="text-center">Purchase Report For ' .$date. '</h3>';  
        }

        $report .='<table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Date</th>
                  <th>PO No</th>
                  <th style="text-align:right">Amount</th>
                </tr>';

        $total_amount=0;
        $row =0;   

        foreach($purchases as $purchase){
            $row++;
            $report .='<tr>
                  <td>'.$row.'</td> 
                  <td>'.$purchase->po_at.'</td>
                  <td>PO'.str_pad($purchase->id , 7, '0', STR_PAD_LEFT).'</td>
                  <td align="right">'.number_format($purchase->total, 2, '.', ',').'</td>
                  </tr>';
                  $total_amount +=$purchase->total;
        }
        $report .='<tr>
            <th colspan="3" style="text-align:right">Total Amount</th>
            <th style="text-align:right">'.number_format($total_amount, 2, '.', ',').'</th>
        </tr>';
         $report .='</table>';

        $report .='  <div class="row no-print">
        <div class="col-xs-12">
          <button type="button" class="btn btn-success pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print
          </button></div></div>';

    }else{
         $report = '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Not enough data to genarate report.
              </div>';
    }
         $report .=' </div>
    </div>
    </div>
    </div>';
        return $report;
    }

     public function getStock()
    {    
        $report = ''; 
        $total_amount=0;
        $rop_products = Product::where('qty','<=','rop')->count();
        $oos_products = Product::where('qty','=',0)->count();
        $products = Product::where('qty','>',0)->get();

        $report .='<table class="table table-bordered">
                <tr>
                  <th style="width: 10px">ID</th>
                  <th>'. trans('table.product') .'</th>
                  <th>'. trans('table.qty') .'</th>
                  <th style="text-align:right">'. trans('table.value') .'</th>
                  <th style="text-align:right">'. trans('table.total') .'</th>
                </tr>';

                foreach($products as $product){
                     $report .='<tr>
                      <td>'. $product->product_id .'</td>
                      <td>'. $product->product_name .'</td>
                      <td>'. $product->qty .'</td>
                      <td align="right">'. number_format($product->cost, 2, '.', ',') .'</td>
                      <td align="right">'. number_format($product->qty * $product->cost, 2, '.', ',') .'</td>
                    </tr>';
                    $total_amount += $product->qty * $product->cost;
                }        
        $report .='<tr>
            <th colspan="4" style="text-align:right">Total Amount</th>
            <th style="text-align:right">'.number_format($total_amount, 2, '.', ',').'</th>
        </tr>';   
        $report .='</table>';
        $report .='  <div class="row no-print">
        <div class="col-xs-12">
          <button type="button" class="btn btn-success pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print
          </button></div></div>';

        return view('reports.stock')
                ->with('report',$report)
                ->with('total',$total_amount)
                ->with('rop_products',$rop_products)
                ->with('oos_products',$oos_products);
    }

}
