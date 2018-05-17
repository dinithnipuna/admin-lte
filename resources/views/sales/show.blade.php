@extends('app')

@section('title', 'iPOS | View Invoice')

@section('page_header')
  <h1> Sales <small>View Invoice</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/sales">Sales</a></li>
    <li class="active">View Invoice</li>
  </ol>
@endsection

@section('content')
  <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> {{ Settings::get('business_name') }}
            <small class="pull-right">Date: {{ date('M j, Y h:iA',strtotime($sale->created_at)) }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>{{ Settings::get('business_name') }}</strong><br>
            {{ Settings::get('business_address_1') }}<br>
            {{ Settings::get('business_address_2') }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong>{{ $sale->customer->name }}</strong><br>
            {{ $sale->customer->address }}<br>
            Phone: {{ $sale->customer->tel }}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice No:</b> SN{{ str_pad($sale->id , 7, '0', STR_PAD_LEFT)}}<br>
          <b>Invoice Date / Time :</b> {{ date('M j, Y h:iA',strtotime($sale->created_at)) }}<br>
          <b>Payment Type :</b> {{ ucfirst($sale->payment_type) }} Payment<br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Item Code</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Discount</th>
              <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sale->products as $product)
                      <tr>
                          <th>{{ $product->product_id }}</th>
                          <td>{{ $product->product_name }} {{ $product->warranty > 0 ? "( ".$product->warranty." Months Warranty )" : "" }}</td>
                          <td>{{ number_format($product->pivot->qty,3) }}</td>
                          <td>{{ number_format($product->pivot->amount,2) }}</td>
                          <td>{{ number_format($product->pivot->discount,2) }}</td>
                          <td>{{ number_format($product->pivot->sub_total,2) }}</td>
                      </tr>
                    @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-xs-4 pull-right">      

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Total Discount :</th>
                <td>{{ number_format($sale->total_discount,2) }}</td>
              </tr>
              @if($sale->payment_type == 'card')
                 <tr>
                <th style="width:50%">Card Payment Charges :</th>
                <td>{{ number_format($sale->charges,2) }}</td>
              </tr>
              @endif
              <tr>
                <th>Total Amount:</th>
                 <td>{{ number_format($sale->total,2) }}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
         <a href="{{route('sales.reciept', $sale->id)}}" data-toggle="tooltip" class="btn btn-danger btn-lg" title="Print Reciept With POS Printer"><i class="fa fa-print"></i> Print Reciept</a>
          <a href="{{route('sales.print', $sale->id)}}" data-toggle="tooltip" class="btn btn-primary btn-lg" title="Print Invoice With Dot Matrix Printer"><i class="fa fa-print"></i> Print Invoice</a>

          @if($sale->status == 0)
             <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#dialog-confirm_complete"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          @endif

         {{--  <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button> --}}
        </div>
      </div>

       <!-- Payment Modal -->
    <div class="modal fade" id="dialog-confirm_complete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
         {!! Form::open(['route' => ['sales.pay'], 'method' => 'POST', 'id' => 'payForm'])!!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Confirm Sale Payment</h4>
          </div>
          <div class="modal-body">
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to pay this ? This cannot be undone.</p>
                   {!! Form::hidden('sale_id',$sale->id , ['id' =>  'sale_id', 'class'=>'form-control'])!!}        
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger" id="pay">Register Payment</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
    </section>
@endsection

@push('scripts') 
  
<script type="text/javascript">

 $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

 var _wasPageCleanedUp = false;
function pageCleanup()
{
    if (!_wasPageCleanedUp)
    {
        $.ajax({
            type: 'POST',
            async: false,
            url: '{!! route('sales.active',$sale->id) !!}',
            success: function ()
            {
                _wasPageCleanedUp = true;
            }
        });
    }
}


$(window).on('beforeunload', function ()
{
    //this will work only for Chrome
    pageCleanup();
});

$(window).on("unload", function ()
{
    //this will work for other browsers
    pageCleanup();
});

  $(document).ready(function() {

    $( "#payForm" ).submit(function( event ) {
   
      // Stop form from submitting normally
      event.preventDefault();
     
      var $form = $( this );

      var url = $form.attr( "action" );
     
      // Send the data using post
      var posting = $.post( url, $form.serialize() );
     
      // Put the results in a div
      posting.done(function( data ) {
         $('#dialog-confirm_complete').modal('hide');
         window.location = "{{ route('sales.index') }}";
      });
    }); 

   $( "#printReciept" ).click(function(event) {
      $.ajax({
                type: "POST",
                url: "{{ route('sales.reciept') }}",
                data: 'id='+{{$sale->id}} ,
                cache: false,
                success: function(data){
                  alert(data);
                  //document.location.href = "{{ route('sales.create')}}"; 
                }
              });   
       // Stop form from submitting normally
      event.preventDefault();
   });

  });
</script>

@endpush 