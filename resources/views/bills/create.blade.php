@extends('app')

@section('title', 'iPOS | Create New Bill')

@section('style') 
  <link rel="stylesheet" href="{{ asset('/plugins/jQueryUI/jquery-ui.css') }}">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="{{ asset('/dist/css/toastr.min.css') }}">
  
  <style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
  <style type="text/css" class="init">
  table.dataTable th,
  table.dataTable td {
    white-space: nowrap;
  }

  </style>
  @endsection


@section('page_header')
  <h1> Sales <small>Create New Bill</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/bills">Bills</a></li>
    <li class="active">Create New Bill</li>
  </ol>
@endsection

@section('content')
  <div class="row">
  <div class="col-md-8">
     <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Create New Bill</h3>
      </div><!-- /.box-header -->
        <div class="box-body">
        <div class="col-xs-3">
            {!! Form::open(array('route' => 'bills.store','class' => 'form-horizontal','id' => 'getProduct')) !!}
             <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>    
                {!! Form::text('term',null,array('id' =>  'term', 'placeholder' =>  'Barcode or ID','class'=>'form-control')) !!}
                </div>
            </div>
           {!! Form::close() !!}
           </div>
            <div class="col-xs-9">                   
               <div class="input-group">
               {!! Form::text('q', '', ['id' =>  'q', 'placeholder' =>  'Enter Product Name or ID', 'class'=>'form-control'])!!}
                  <span class="input-group-btn">
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addProductModal"><i class="glyphicon glyphicon-plus"></i> Add Product</button>
                  </span>
               </div>
               </div>

               <table id="invoiceTable" class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Discount</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        @if(Session::has('cart'))
          <tbody>
           @foreach($products as $product)
            <tr>
              <td>{{ $product['item']['id'] }}</td>
              <td>{{ $product['item']['product_name'] }}</td>
              <td id="{{ $product['item']['id'] }}">{{ $product['qty'] }}</td>
              <td>{{ $product['unit_price'] }}</td>
              <td id="{{ $product['item']['id'] }}">{{ $product['discount'] }}</td>
              <td>{{ $product['amount'] }}</td>
              <td><a title="Delete this item" class="delete btn btn-danger" id="{{ $product['item']['id'] }}"><i class="glyphicon glyphicon-trash"></i></a></td>
            </tr>
             @endforeach
          </tbody>

        @endif
    </table>
</div>
              </div><!-- /.box -->
</div>

<div class="col-md-4">
 <div class="box box-primary">
    <div class="box-header with-border">
                  <h3 class="box-title">Customer Details</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
      
          <div class="input-group">    
               {!! Form::text('customers', '', ['id' =>  'customers', 'placeholder' =>  'Search Customer...', 'class'=>'form-control'])!!}
               <span class="input-group-btn">
                      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addCustomerModal"><i class="glyphicon glyphicon-plus"></i></button>
                    </span>
                   </div>
               {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}

                </div>
 </div>

 <div class="box box-success">
    <div class="box-header with-border">
                  <h3 class="box-title">Payment Type</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                   {!! Form::select('payment_type', array('cash' => 'Cash Payment','card' => 'Card Payment' ,'cheque' => 'Cheque Payment'), 'cash', ['id' => 'payment_type','class' => 'form-control','v-model'=> 'payment_type', 'v-on:change'=>'greet']) !!} 
                </div>
 </div>



 <div class="box box-danger">
    <div class="box-header with-border">
                  <h3 class="box-title">Payment Details</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <ul class="list-group">
  <li class="list-group-item list-group-item-success">No of items  <span class="badge" id="items-count">{{ Session::has('cart') ? Session::get('cart')->totalQty : 0}}</span></li>
  <li class="list-group-item list-group-item-warning">Total Discount  <span class="badge" id="total-discount">{{ Session::has('cart') ? Session::get('cart')->totalDiscount : 0.00}}</span></li>
  <li class="list-group-item list-group-item-warning">Card Payment Charge  <span class="badge" id="card_payment_charge">0.00</span></li>
  <li class="list-group-item list-group-item-info"><h2>Total Amount  <span id="sub-total" class="pull-right label label-primary">{{ Session::has('cart') ? Session::get('cart')->totalAmount : 0.00}}</span></h2></li>
 
</ul>
               
                
                  <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#dialog-confirm_complete"><i class="glyphicon glyphicon-ok"></i> Complete Sale</button>
                  <button class="btn btn-block btn-danger" data-toggle="modal" data-target="#dialog-confirm_suspend"><i class="glyphicon glyphicon-remove"></i> Suspend Sale</button>
                </div>

 </div>

</div>
</div>


 <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(array('route' => 'products.store','class' => 'form-horizontal','id' => 'addProduct')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Product</h4>
      </div>
      <div class="modal-body">
                    
                    <div class="form-group">
                      {!! Form::label('product_id','Product ID',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_id',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>
      
                    <div class="form-group">
                      {!! Form::label('product_name','Product Name',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('barcode','Barcode',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('barcode',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('uom_id','Unit of Measure',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                         <select class="form-control" name="uom_id">
                                <option value=""></option>
                              @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                         </select>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('qty','Quantity',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('qty',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('cost','Cost',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('cost',null,array('class'=>'form-control')) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('sale','Selling Price',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                       <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('sale',null,array('class'=>'form-control')) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('warranty','Warranty',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                       <div class="input-group">
                        {!! Form::text('warranty',null,array('class'=>'form-control' , 'required')) !!}
                        <span class="input-group-addon">Month(s)</span>
                      </div>  
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('rop','Reorder Level',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('rop',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         {!! Form::submit('Create',array('class'=>'btn btn-info pull-right')) !!}
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(array('route' => 'customers.store','class' => 'form-horizontal','id' => 'addCustomer')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Customer</h4>
      </div>
      <div class="modal-body">
                    
                    <div class="form-group">
                      {!! Form::label('name','Customer Name',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('job','Job Position',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('job',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('address','Address',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('address',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('tel','Telephone No',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('tel',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('mobile','Mobile',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('mobile',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    {!! Form::hidden('ajax_post', 'true', array('id' => 'ajax_post')) !!}


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         {!! Form::submit('Create',array('class'=>'btn btn-info pull-right')) !!}
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="dialog-confirm_suspend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Suspend Sale</h4>
      </div>
      <div class="modal-body">
        <p><span class="glyphicon glyphicon-question-sign" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to suspend this sale? This cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="suspend-sale">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="dialog-confirm_complete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Complete Sale</h4>
      </div>
      <div class="modal-body">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to submit this sale? This cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="complete-sale">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts') 

  <script src="{{ asset('/plugins/jQueryUI/jquery-ui.js') }}"></script>
  <!-- JEditable -->
  <script src="{{ asset('/plugins/datatables/jquery.jeditable.js') }}"></script>
  <!-- DataTables -->
  <script type="text/javascript" src="{{ asset('/plugins/datatables/jquery.dataTables.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/plugins/datatables/dataTables.bootstrap.js') }}"></script>
  <!-- Toasrt -->
  <script src="{{ asset('/dist/js/toastr.min.js') }}"></script> 
  
<script type="text/javascript">

var app = new Vue({
  el: '#app',
  data: {
    payment_type : 'cash'
  },
    // define methods under the `methods` object
  methods: {
    greet: function (event) {
      // `this` inside methods points to the Vue instance
      alert('Hello ' + this.payment_type + '!')
    }
  }
})


$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});

$(document).ajaxStart(function() { Pace.restart(); });

$( "#target" ).submit(function( event ) {
    event.preventDefault();

              $.post('{!! route('customers.store') !!}', $.param($(this).serializeArray()), function(data) {
                    $('#myModal').modal('hide');         
              });
});

$(document).ready(function() {

$("#term").focus();

var invoice = [];

var oTable = $('#invoiceTable').DataTable( {
"paging":   false,
"ordering": false,
"info":     false,
"searching": false,
"columnDefs": [
              { className: "qty", "targets": [2] },
              { className: "sale", "targets": [3] },
              { className: "discount", "targets": [4] }
            ],  
"language": {
              "emptyTable": "<h3 class='text-center text-warning'>There are no items in the cart [Sales]</h3>"
            },
"fnDrawCallback" : function() {

      $('.qty').editable(function(value, settings) {
                 if(isNaN(value)){
                   return parseFloat("1").toFixed(3);
                }else{
                   return parseFloat(value).toFixed(3);
                }
      },{
            callback : function(value, settings) {
              var rowIdx = oTable.row($(this).parents('tr')).index();
              var column = "qty";
              updateTable(rowIdx, value, column);
            },
            select : true
      });
  

      $('.sale').editable(function(value, settings) {
               return parseFloat(value).toFixed(3);
      },{
             callback : function(value, settings) {
              var rowIdx = oTable.row($(this).parents('tr')).index();
              var column = "sale";
              updateTable(rowIdx, value, column);
            },
            select : true
      });


       $('.discount').editable(function(value, settings) {
               return parseFloat(value).toFixed(2);
      },{
             callback : function(value, settings) {
              var rowIdx = oTable.row($(this).parents('tr')).index();
              var column = "discount";
              updateTable(rowIdx, value, column);
            },
            select : true
      });

  }
});

 function updateTable(rowIdx, value, column) {
    var id = oTable.cell( rowIdx, 0).data();
    var qty,sale,discount = 0;

    for (i = 0; i < invoice.length; i++) { 
      if (invoice[i]['product_id'] == id){
        if(column == "qty"){
          invoice[i]['qty'] = value ;
          qty = value;
          sale = invoice[i]['sale'];
          discount = invoice[i]['discount'];
        }else if(column == "sale"){
          invoice[i]['sale'] = value ; 
          sale = value;
          qty = invoice[i]['qty'];
          discount = invoice[i]['discount'];
        }else if(column == "discount"){
          sale = invoice[i]['sale'];
          qty = invoice[i]['qty'];
          invoice[i]['discount'] = value ; 
          discount = value * qty;
        }     
          invoice[i]['amount'] = (sale * qty) - discount ;
      }   
    }

    var amount = (sale * qty) - discount;
    var colAmount = oTable.cell( rowIdx, 5);
    colAmount.data( amount.toFixed(2) ).draw();
    $("#items-count").html(getItems());
    $("#total-discount").html(getTotalDiscount());
    $("#sub-total").html(getTotal());
    $("#term").focus();
}



  $( "#addProduct" ).submit(function( event ) {
        event.preventDefault();

        $.post('{!! route('products.store') !!}', $.param($(this).serializeArray()), function(data) {

              $('#addProductModal').modal('hide');

              var id = parseInt(data.item.id);
              var item_code = data.item.product_id;
              var description = data.item.product_name;
              var sale = parseInt(data.item.sale).toFixed(2);
              var stock = parseInt(data.item.stock);
              var discount = parseInt("0").toFixed(2);

             oTable.row.add( [
                  id,
                  item_code+" - "+description,
                  parseFloat("1").toFixed(3),
                  sale,
                  discount,
                  sale,
                  '<a class="delete btn btn-danger" id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>'
              ] ).draw( false );

             var product = {'product_id' : id, 'qty' : 1, 'sale' : sale, 'discount' : discount ,'amount' : sale, 'stock' : stock};
             invoice.push(product);

             $("#items-count").text(getItems());
             $("#total-discount").html(getTotalDiscount());
             $("#sub-total").text(getTotal());
        });
    });

   $( "#addCustomer" ).submit(function( event ) {
        event.preventDefault();

        $.post('{!! route('customers.store') !!}', $.param($(this).serializeArray()), function(data) {
            $('#addCustomerModal').modal('hide');
            $("#customers").val(data.name);
            $("#customer_id").val(data.id);     
        });
    });

    $( "#getProduct" ).submit(function( event ) {
        event.preventDefault();

        $.get('{!! route('sales.product') !!}', $.param($(this).serializeArray()), function(data) {        
           addItems(event,data);
        }).fail(function() {
           toastr.error("Invalid Product", "Error ");
           $('#term').val('');
        });

        $("#term").focus();
    });
     

   $( "#q" ).autocomplete({
    autoFocus: true,
    selectFirst:true,
    source: '{!! route('search.data') !!}',
    minLength: 1,
    select: function(event, ui) {
      addItems(event,ui.item);
      $("#term").focus();
    }

  });

    $( "#customers" ).autocomplete({
        autoFocus: true,
      source: '{!! route('customer.data') !!}',
      minLength: 2,
      select: function( event, ui ) {
          $('#customer_id').val(ui.item.id);
      }
    });

    $( document ).delegate( "#invoiceTable tbody td a.delete", "click", function() {
       for (i = 0; i < invoice.length; i++) { 
        if (invoice[i]['product_id'] == this.id) invoice.splice(i, 1);    
      }
       oTable.row($(this).parents('tr')).remove().draw();   
       $("#items-count").html(getItems());
       $("#total-discount").html(getTotalDiscount());
       $("#sub-total").html(getTotal());
    });

    $( "#payment_type" ).change(function() {
        $("#card_payment_charge").html('0.00');
        $("#sub-total").text(getTotal());
    });

    function addItems(event, data){

      var id = parseInt(data.id);
      var item_code = data.product_id;
      var sale = parseInt(data.sale).toFixed(2);
      var stock = parseInt(data.qty);
      var discount = parseInt("0").toFixed(2);

      if(stock > 0){

      }else{
          toastr.warning("Insufficient Stock !!!", "Warning "); 
      }
      
        if(inArray(id)){
          for (i = 0; i < invoice.length; i++) { 
            if (invoice[i]['product_id'] == id){
                invoice[i]['qty'] = parseFloat(invoice[i]['qty'])+ 1;   
                invoice[i]['amount'] = invoice[i]['qty'] * (invoice[i]['sale'] - invoice[i]['discount']);
                var rowIdx = oTable.column(0, { order: 'index'}).data().indexOf(id); 
                oTable.cell( rowIdx, 2).data(invoice[i]['qty'].toFixed(3)).draw();
                oTable.cell( rowIdx, 4).data(invoice[i]['discount']).draw();
                oTable.cell( rowIdx, 5).data(invoice[i]['amount'].toFixed(2)).draw();
            }    
          }
        }else{
          
          oTable.row.add( [
            id,
            item_code+" - "+data.product_name,
            parseFloat("1").toFixed(3),
            sale,
            discount,
            sale,
            '<a class="delete btn btn-danger" id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>'
        ] ).draw( false );
       var product = {'product_id' : id, 'qty' : 1, 'sale' : sale, 'discount' : discount ,'amount' : sale, 'stock' : stock};
       invoice.push(product);
       
       }

      $('#term').val('');
      $('#q').val('');
        
      event.preventDefault();
      
       $("#items-count").text(getItems());
       $("#total-discount").html(getTotalDiscount());
       $("#sub-total").text(getTotal());
      
    }

    function getItems() {
      return invoice.length;       
    }

    function getTotal() {
      var total = 0;
      var charge =0;
      for (i = 0; i < invoice.length; i++) { 
          total = total + parseInt(invoice[i]['amount']);
      }  

      if($("#payment_type").val() == 'card'){
        card_payment_charge = {{ Settings::get('card_payment_charge') }};
        charge = (total/100)*parseFloat(card_payment_charge);
        total = total+ charge;
        $("#card_payment_charge").html(charge.toFixed(2));
      }

      return total.toFixed(2);  

    }


     function getTotalDiscount() {
      var total_discount = 0;
      for (i = 0; i < invoice.length; i++) { 
          total_discount = total_discount + parseInt(invoice[i]['discount']);
      }  
      return total_discount.toFixed(2);  

    }

    function inArray(id){
      for (i = 0; i < invoice.length; i++) { 

        if (invoice[i]['product_id'] == id){
              return true;      
        }
      }

    }


    $( document ).delegate("#complete-sale","click",function(e){
        var total_amount=$("#sub-total").text(); 
        var card_payment_charge=$("#card_payment_charge").text(); 
        var total_discount=$("#total-discount").text();   
        var customer_id = $("#customer_id").val();
        var payment_type = $("#payment_type").val();
        var items = JSON.stringify(invoice);

          if($.isEmptyObject(invoice)){
              $('#dialog-confirm_complete').modal('hide');
              toastr.warning("Sale Order is empty", "Warning "); 
          }else if(!customer_id){
            $('#dialog-confirm_complete').modal('hide');
              toastr.warning("Please select a customer", "Warning "); 
          }else{


        var dataString = "total_amount="+total_amount+"&total_discount="+total_discount+"&card_payment_charge="+card_payment_charge+"&customer_id="+customer_id+"&payment_type="+payment_type+"&items="+items;
        $.ajax({
              type: "POST",
              url: '{!! route('bills.store') !!}',
              data: dataString,
              cache: false,
              success: function(data){ 
                $('#dialog-confirm_complete').modal('hide');
                window.location = data;
              }
          });

          }
      });


    $( document ).delegate("#suspend-sale","click",function(e){      
              $.ajax({
              type: "POST",
              url: '{!! route('cart.clear') !!}',
              data: '',
              cache: false,
              success: function(data){ 
                location.reload();
              }
          });

      });
});


</script>
@endpush 