@extends('app')

@section('title', 'iPOS | Edit Purchase Order')

@section('style') 
  <link rel="stylesheet" href="{{ asset('/plugins/jQueryUI/jquery-ui.css') }}">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">

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
  @endsection


@section('page_header')
 <h1> Purchases <small>Edit Purchase Order</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/purchases">Purchases</a></li>
    <li class="active">Edit Purchase Order</li>
  </ol>
@endsection

@section('content')
  <div class="row">
   
    <div class="col-md-8">
     <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Purchase Order</h3>
                </div><!-- /.box-header -->
                                <div class="box-body">

               {!! Form::open(['route' => ['purchases.store'], 'method' => 'POST'])!!}
               <div class="input-group">
               {!! Form::text('q', '', ['id' =>  'q', 'placeholder' =>  'Enter Product Name or ID', 'class'=>'form-control'])!!}
                  <span class="input-group-btn">
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addProductModal"><i class="glyphicon glyphicon-plus"></i> Add New Product</button>
                  </span>
               </div>
               {!! Form::close() !!}

                <table id="invoiceTable" class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th class="col-sm-5">Product</th>
                          <th>Quantity</th>
                          <th>Unit Price</th>
                          <th>Subtotal</th>
                          <th class="col-sm-1"></th>
                      </tr>
                  </thead>
        <tbody>
            @foreach($purchase->products as $product)
                      <tr>
                          <th>{{ $product->id }}</th>
                          <td>{{ $product->product_id }} - {{ $product->product_name }} {{ $product->warranty > 0 ? "( ".$product->warranty." Months Warranty )" : "" }}</td>
                          <td>{{ number_format($product->pivot->qty,3) }}</td>
                          <td>{{ number_format($product->pivot->amount,2) }}</td>
                          <td>{{ number_format($product->pivot->sub_total,2) }}</td>
                          <td><a class="delete btn btn-danger disabled" id="{{ $product->id }}"><i class="glyphicon glyphicon-trash"></i></a></td>
                      </tr>
                    @endforeach
            </tbody>
              </table>
</div>
              </div><!-- /.box -->
</div>

    <div class="col-md-4">
       <div class="box box-primary">
          <div class="box-header with-border">
                        <h3 class="box-title">Purchase Order Details</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
            {!! Form::open(['route' => ['purchases.store'], 'method' => 'POST'])!!}
               <div class="form-group">
                        
                       
                         <div class="input-group">
                         {!! Form::hidden('id',$purchase->id , ['id' =>  'purchase_id', 'class'=>'form-control'])!!}
                            {!! Form::text('suppliers', $purchase->supplier->name, ['id' =>  'suppliers', 'placeholder' =>  'Search Supplier...', 'class'=>'form-control'])!!}
                             <span class="input-group-btn">
                      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addSupplierModal"><i class="glyphicon glyphicon-plus"></i></button>
                    </span>
                   </div>
                            {!! Form::hidden('supplier_id', $purchase->supplier->id, array('id' => 'supplier_id')) !!}
                      </div> 

                       <div class="form-group">
                            {!! Form::text('sup_ref',$purchase->sup_ref,array('class'=>'form-control', 'placeholder' =>  'Enter Supplier Reference', 'id' => 'sup_ref')) !!}
                      </div>

                       <div class="form-group">
                          <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                           {!! Form::text('po_at',$purchase->po_at,array('class'=>'form-control pull-right', 'id' => 'po_at')) !!}
                          </div>
                          <!-- /.input group -->
                        </div>
                     {!! Form::close() !!}

                      </div>

       </div>

       <div class="box box-danger">
          <div class="box-header with-border">
                        <h3 class="box-title">Payment Details</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                      <ul class="list-group">
        <li class="list-group-item list-group-item-success">No of items  <span class="badge" id="items-count">0</span></li>
        <li class="list-group-item list-group-item-warning">Total Amount  <span class="badge" id="sub-total">0.00</span></li>      
      </ul>
                     
                      
                        <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#dialog-confirm_complete"><i class="glyphicon glyphicon-ok"></i> Update Order</button>
                        <a href="{{ URL::previous() }}" class="btn btn-block btn-default"><i class="fa fa-reply"></i> Go Back</a>
                      </div>

       </div>

</div>


 <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(array('route' => 'products.store','class' => 'form-horizontal','id' => 'target')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('header.add_new_product') }}</h4>
      </div>
      <div class="modal-body">
                    
                    <div class="form-group">
                      {!! Form::label('product_id',trans('form.product_id'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_id',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>
      
                    <div class="form-group">
                      {!! Form::label('product_name',trans('form.product_name'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_name',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('product_name_alt',trans('form.product_name_alt'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_name_alt',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('product_type',trans('form.section'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::select('product_type', ['bp_bread' => trans('form.bp_bread'),'bp_pastry' => trans('form.bp_pastry'),'bp_cake' => trans('form.bp_cake'),'consumable' => trans('form.consumable'), 'beverage' => trans('form.beverage'), 'raw_material' =>trans('form.raw_material'), 'other' =>trans('form.other')], 'consumable',array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('uom_id',trans('form.uom'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                         <select class="form-control" name="uom_id" required>
                                <option value=""></option>
                              @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                         </select>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('qty',trans('form.qty'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('qty',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('cost',trans('form.cost'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('cost',null,array('class'=>'form-control', 'required')) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('sale',trans('form.sale'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                       <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('sale',null,array('class'=>'form-control' , 'required')) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('warranty',trans('form.warranty'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                       <div class="input-group">
                        {!! Form::text('warranty',null,array('class'=>'form-control' , 'required')) !!}
                        <span class="input-group-addon">Month(s)</span>
                      </div>  
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('rop',trans('form.rop'),array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('rop',null,array('class'=>'form-control' , 'required')) !!}
                      </div>  
                    </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.close') }}</button>
         {!! Form::submit(trans('button.create'),array('class'=>'btn btn-primary pull-right')) !!}
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(array('route' => 'suppliers.store','class' => 'form-horizontal','id' => 'addSupplier')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Supplier</h4>
      </div>
      <div class="modal-body">
                    
                    <div class="form-group">
                      {!! Form::label('name','Supplier Name',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
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
        <h4 class="modal-title" id="myModalLabel">Confirm Suspend Order</h4>
      </div>
      <div class="modal-body">
        <p><span class="glyphicon glyphicon-question-sign" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to suspend this order? This cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="suspend-order">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="dialog-confirm_complete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Complete Order</h4>
      </div>
      <div class="modal-body">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to submit this order? This cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="complete-order">Yes</button>
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

  <script src="{{ asset('/dist/js/toastr.min.js') }}"></script> 
  <!-- bootstrap datepicker -->
  <script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">


$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});

$(document).ajaxStart(function() { Pace.restart(); });

$(document).ready(function() {

    //Date picker
    $('#po_at').datepicker({
      autoclose: true,
      todayHighlight : true,
      format :  "yyyy-mm-dd"
    });

   $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
    if(e.which == 13) {
      e.preventDefault();
      return false;
    }
  });
 
    var invoice = <?php echo json_encode($invoice) ?> ;

    $("#items-count").html(getItems());
    $("#sub-total").html(getTotal());

    var oTable = $('#invoiceTable').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "columnDefs": [
                      { className: "qty", "targets": [2] },
                      { className: "cost", "targets": [3] }
                    ],  
        "language": {
                      "emptyTable": "<h3 class='text-center text-warning'>There are no items in the cart [{{ trans('header.purchases') }}]</h3>"
                    },  

        "fnDrawCallback" : function() {

            var qty;
            var cost;
              $('.qty').editable(function(value, settings) {
                         if(isNaN(value)){
                           return parseFloat("1").toFixed(3);
                        }else{
                           return parseFloat(value).toFixed(3);
                        }
              },{
                     callback : function(value, settings) {
                      var rowIdx = oTable.row($(this).parents('tr')).index();
                      var id = oTable.cell( rowIdx, 0).data();
                      for (i = 0; i < invoice.length; i++) { 
                        if (invoice[i]['product_id'] == id){
                          invoice[i]['qty'] = value ;
                          qty = value;
                          cost = invoice[i]['cost'];
                          invoice[i]['amount'] = qty * cost;
                        }   
                      }
                      var amount = qty * cost;
                      var colAmount = oTable.cell( rowIdx, 4);
                      colAmount.data( amount.toFixed(2) ).draw();
                      $("#items-count").html(getItems());
                      $("#sub-total").html(getTotal());
                },
                select : true
              });

              $('.cost').editable(function(value, settings) {
                        if(isNaN(value)){
                           return parseFloat("0").toFixed(2);
                        }else{
                           return parseFloat(value).toFixed(2);
                        }
              },{
                     callback : function(value, settings) {
                      var rowIdx = oTable.row($(this).parents('tr')).index();
                      var id = oTable.cell( rowIdx, 0).data();
                       for (i = 0; i < invoice.length; i++) { 
                          if (invoice[i]['product_id'] == id) {
                            invoice[i]['cost'] = value ; 
                            cost = value;
                            qty = invoice[i]['qty'];
                            invoice[i]['amount'] = qty * cost;
                          }
                        }
                         var amount = qty * cost;
                          var colAmount = oTable.cell( rowIdx, 4);
                         colAmount.data( amount.toFixed(2)).draw();
                        $("#items-count").html(getItems());
                         $("#sub-total").html(getTotal());
                },
                select : true
              });

          }
        });

    $( "#target" ).submit(function( event ) {
        event.preventDefault();

        $.post('{!! route('products.store') !!}', $.param($(this).serializeArray()), function(data) {
              $('#myModal').modal('hide');

              var id = parseInt(data.item.id);
              var item_code = data.item.product_id;
              var description = data.item.product_name;
              var cost = parseFloat(data.item.cost).toFixed(2);

              oTable.row.add( [
                  id,
                  item_code +" - "+description,
                  parseFloat("1").toFixed(3),
                  cost,
                  cost,
                  '<a class="delete btn btn-danger" id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>'
              ] ).draw( false );

              var product = {'product_id' : id, 'qty' : 1, 'cost' : cost, 'amount' : cost};
              invoice.push(product);

             $("#items-count").text(getItems());
             $("#sub-total").text(getTotal());     
        });
    });

     $( "#addSupplier" ).submit(function( event ) {
        event.preventDefault();

        $.post('{!! route('suppliers.store') !!}', $.param($(this).serializeArray()), function(data) {
            $('#addSupplierModal').modal('hide');
            $("#suppliers").val(data.name);
            $("#supplier_id").val(data.id);     
        });
    });
    

   $( "#q" ).autocomplete({
    autoFocus: true,
    source: '{!! route('search.data') !!}',
    minLength: 1,
    select: function(event, ui) {
      var id = parseInt(ui.item.id);
      var item_code = ui.item.product_id;
      var cost = parseFloat(ui.item.cost).toFixed(2);
      var stock = parseInt(ui.item.stock);
        if(inArray(id)){
          for (i = 0; i < invoice.length; i++) { 
            if (invoice[i]['product_id'] == id){
                invoice[i]['qty'] = parseFloat(invoice[i]['qty'])+ 1;  
                invoice[i]['amount'] = invoice[i]['qty'] * invoice[i]['cost'];
                //var rowIdx = oTable.column(0, { order: 'index'}).data().indexOf(id);
                var rowIdx = i; 
                oTable.cell( rowIdx, 2).data(invoice[i]['qty'].toFixed(3)).draw();
                oTable.cell( rowIdx, 4).data(invoice[i]['amount'].toFixed(2)).draw();
            }    
          }
        }else{
          
          oTable.row.add( [
            id,
            item_code+" - "+ui.item.value,
            parseFloat("1").toFixed(3),
            cost,
            cost,
            '<a class="delete btn btn-danger" id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>'
        ] ).draw( false );
       var product = {'product_id' : id, 'qty' : 1, 'cost' : cost, 'amount' : cost};
       invoice.push(product);
       }
       $("#items-count").text(getItems());
       $("#sub-total").text(getTotal());


        $('#q').val('');
        event.preventDefault();
    }
  });

       $( "#suppliers" ).autocomplete({
        autoFocus: true,
      source: '{!! route('supplier.data') !!}',
      minLength: 2,
      select: function( event, ui ) {
          $('#supplier_id').val(ui.item.id);
      }
    });

    $( document ).delegate( "#invoiceTable tbody td a.delete", "click", function() {
      for (i = 0; i < invoice.length; i++) { 
        if (invoice[i]['product_id'] == this.id) invoice.splice(i, 1);    
      }
       oTable.row($(this).parents('tr')).remove().draw();   
       $("#items-count").html(getItems());
       $("#sub-total").html(getTotal());
    });

    function getItems() {
      return invoice.length;      
    }

    function getTotal() {
      var total = 0;
      for (i = 0; i < invoice.length; i++) { 
          total = total + parseInt(invoice[i]['amount']);
      }  
      console.log(total); 
      return total.toFixed(2);  

    }

    function inArray(id){
      for (i = 0; i < invoice.length; i++) { 

        if (invoice[i]['product_id'] == id){
              return true;      
        }
      }

    }


    $( document ).delegate("#complete-order","click",function(e){
        var id = $("#purchase_id").val();
        var total_amount=$("#sub-total").text();  
        var supplier_id = $("#supplier_id").val();
        var sup_ref = $("#sup_ref").val();
        var po_date = $("#po_at").val();
        var items = JSON.stringify(invoice);



          if($.isEmptyObject(invoice)){
              $('#dialog-confirm_complete').modal('hide');
              toastr.warning("Purchase Order is empty.", "Warning ");
          }else if(!supplier_id){
              $('#dialog-confirm_complete').modal('hide');
              toastr.warning("Please select a supplier", "Warning ");
          }else if(!po_date){
              $('#dialog-confirm_complete').modal('hide');
              toastr.warning("Please select Order Date", "Warning ");
          }else{


         var dataString = "id="+id+"&total_amount="+total_amount+"&supplier_id="+supplier_id+"&sup_ref="+sup_ref+"&po_date="+po_date+"&items="+items;

        $.ajax({
              type: "PUT",
              url: '{!! route('purchases.update') !!}',
              data: dataString,
              cache: false,
              success: function(data){ 
                $('#dialog-confirm_complete').modal('hide')
                  window.location = data;
              }
          });

          }
      });

    $( document ).delegate("#suspend-order","click",function(e){      
                location.reload();    
      });
});


</script>
@endpush 