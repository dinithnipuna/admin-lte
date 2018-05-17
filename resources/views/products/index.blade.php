@extends('layouts.app')

@section('title', 'iPOS | List Of Products')

@section('style') 
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
<style type="text/css" class="init">
  table.dataTable th,
  table.dataTable td {
    white-space: nowrap;
  }

  </style>
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('page_header')
  <h1> Products <small>List Of Products</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">List Of Products</li>
  </ol>
@endsection

@section('content')
 <div class="row">
        <div class="col-xs-12">
  <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="col-md-6">
                    <a href="" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus"></i> Add New Product</a>
                  </div>
                   <div class="col-md-6" id="message"></div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="products-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>UOM</th>
                <th>Cost</th>
                <th>Sale</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
                  
    </div><!-- /.box-body -->
 </div>   
 </div>
 </div> 

 <!-- Add Product Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(array('route' => 'products.store','class' => 'form-horizontal','id' => 'target')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Product</h4>
      </div>
      <div class="modal-body">
                    
                    <div class="form-group">
                      {!! Form::label('product_id','Product ID',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_id',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>
      
                    <div class="form-group">
                      {!! Form::label('product_name','Product Name',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_name',null,array('class'=>'form-control', 'required')) !!}
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
                         <select class="form-control" name="uom_id" required>
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
                        {!! Form::text('qty',null,array('class'=>'form-control', 'required')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('cost','Cost',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('cost',null,array('class'=>'form-control', 'required')) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('sale','Selling Price',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                       <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('sale',null,array('class'=>'form-control' , 'required')) !!}
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
         {!! Form::submit('Create',array('class'=>'btn btn-primary pull-right')) !!}
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>

 <!-- Edit Product Modal -->
<div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    {!! Form::open(array('method' => 'PUT','class' => 'form-horizontal','id'=>'editForm')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Product</h4>
      </div>
      <div class="modal-body">
                    {!! Form::hidden('id',null , ['id' =>  'id', 'class'=>'form-control'])!!}    
                    <div class="form-group">
                      {!! Form::label('product_id','Product ID',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('product_id',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('product_name','Product Name',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
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
                      {!! Form::label('uom_id','Unit of Measure',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        <select class="form-control" name="uom_id" id="uom_id">
                                <option value=""></option>
                              @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                         </select>
                      </div>
                    </div>


                     <div class="form-group">
                      {!! Form::label('qty','Quantity',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                        {!! Form::text('qty',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('cost','Cost',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
                      <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        {!! Form::text('cost',null,array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('sale','Sale Price',array('class' => 'col-sm-3 control-label')) !!}
                      <div class="col-sm-9">
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
                        {!! Form::text('warranty',null,array('class'=>'form-control')) !!}
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
         {!! Form::submit('Update',array('class'=>'btn btn-primary pull-right')) !!}
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>

 <!-- Delete Product Modal -->
<div class="modal fade" id="deleteProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(['method' => 'DELETE', 'id' => 'deleteForm'])!!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Deletion</h4>
      </div>
      <div class="modal-body">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete this ? This cannot be undone.</p>
               {!! Form::hidden('id',null , ['id' =>  'id', 'class'=>'form-control'])!!}        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" id="pay">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div> 

@endsection

@push('scripts')
<!-- DataTables -->
<script type="text/javascript" src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/dataTables.bootstrap.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script> 
    <!-- page script -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $(function() {
            var oTable = $('#products-table').DataTable({
               responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('products.data') !!}'
            });

            $( "#target" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('products.store') !!}', $.param($(this).serializeArray()), function(data) {
                    oTable.ajax.reload();
                    $('#myModal').modal('hide');
                    toastr.success(data.success);       
              });
            });

            $('#editProduct').on('shown.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget) // Button that triggered the modal
                var productid = button.data('productid') // Extract info from data-* attributes

                  $.get('{!! route('products.edit',['id' => 0]) !!}', {id:productid}, function(data) {
                          modal.find('.modal-body #id').val(data.id);
                          modal.find('.modal-body #product_id').val(data.product_id);
                          modal.find('.modal-body #product_name').val(data.product_name);
                          modal.find('.modal-body #barcode').val(data.barcode);
                          modal.find('.modal-body #uom_id').val(data.uom_id);
                          modal.find('.modal-body #qty').val(data.qty);
                          modal.find('.modal-body #cost').val(data.cost);
                          modal.find('.modal-body #sale').val(data.sale);
                          modal.find('.modal-body #warranty').val(data.warranty);
                          modal.find('.modal-body #rop').val(data.rop);
                  });
            });

            $( "#editForm" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('products.update',['id' => 0]) !!}', $.param($(this).serializeArray()), function(data) {
                    $('#editProduct').modal('hide');
                    oTable.ajax.reload();
                    toastr.success(data);       
              });
            });

            $('#deleteProduct').on('shown.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id = button.data('productid') // Extract info from data-* attributes
                modal.find('.modal-body #id').val(id);                
            });

             $( "#deleteForm" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('products.destroy',['id' => 0]) !!}', $.param($(this).serializeArray()), function(data) {
                    $('#deleteProduct').modal('hide');
                    oTable.ajax.reload();
                    toastr.success(data);        
              });
            });

        });
    </script>
@endpush