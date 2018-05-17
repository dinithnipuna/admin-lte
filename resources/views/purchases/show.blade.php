@extends('app')

@section('title', 'iPOS | View Purchase Order')

@section('page_header')
  <h1> Purchases <small>View Purchase Order</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/purchases">Purchases</a></li>
    <li class="active">View Purchase Order</li>
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
            <small class="pull-right">Date: {{ date('M j, Y h:iA',strtotime($purchase->created_at)) }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
      <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>{{ $purchase->supplier->name }}</strong><br>
            {{ $purchase->supplier->address }}<br>
            Phone: {{ $purchase->supplier->tel }}<br>
          </address>
        </div>

        <div class="col-sm-4 invoice-col">
          To
          <address>
             <strong>{{ Settings::get('business_name') }}</strong><br>
            {{ Settings::get('business_address_1') }}<br>
            {{ Settings::get('business_address_2') }}
          </address>
        </div>
        
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Order ID:</b>PO{{ str_pad($purchase->id  , 7, '0', STR_PAD_LEFT)}}<br>
          <b>Supplier Reference:</b> {{ $purchase->sup_ref }}<br>
          <b>Order Date:</b> {{ $purchase->po_at }}<br>
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
              <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($purchase->products as $product)
                      <tr>
                            <th>{{ $product->product_id }}</th>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ number_format($product->pivot->qty,3) }}</td>
                            <td>Rs.{{ number_format($product->pivot->amount,2) }}</td>
                            <td>Rs.{{ number_format($product->pivot->sub_total,2) }}</td>
                        </tr>
                    @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead"></p>

          <div class="table-responsive">
            <table class="table">
              <!-- <tr>
                <th style="width:50%">Subtotal:</th>
                <td>Rs.{{ number_format($purchase->total,2) }}</td>
              </tr>
              <tr>
                <th>Tax (9.3%)</th>
                <td>$10.34</td>
              </tr>
              <tr>
                <th>Shipping:</th>
                <td>$5.80</td>
              </tr> -->
              <tr>
                <th>Total:</th>
                <td>Rs.{{ number_format($purchase->total,2) }}</td>
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
         {{--  <a href="invoice-print.html" target="_blank" class="btn btn-danger"><i class="fa fa-print"></i> Print</a> --}}

          @if($purchase->status == 0)
             <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#dialog-confirm_complete"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          @endif
         
         {{--  <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button> --}}
        </div>
      </div>
    </section>

    <!-- Payment Modal -->
<div class="modal fade" id="dialog-confirm_complete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(['route' => ['purchases.pay'], 'method' => 'POST', 'id' => 'payForm'])!!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Order Payment</h4>
      </div>
      <div class="modal-body">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to pay this ? This cannot be undone.</p>
               {!! Form::hidden('purchase_id',$purchase->id , ['id' =>  'purchase_id', 'class'=>'form-control'])!!}        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" id="pay">Register Payment</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection

@push('scripts') 
  
<script type="text/javascript">
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
         window.location = "{{ route('purchases.index') }}";
      });
    }); 

  });
</script>

@endpush 