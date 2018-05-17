@extends('app')

@section('title', 'iPOS | List Of Customers')

@section('style') 
<link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
<style type="text/css" class="init">
  table.dataTable th,
  table.dataTable td {
    white-space: nowrap;
  }

  </style>

@endsection

@section('page_header') 
  <h1> Customers <small>List Of Customers</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">List Of Customers</li>
  </ol>
@endsection

@section('content') 
<div class="box box-primary">
                <div class="box-header with-border">
                  <div class="col-md-10">
                    <a href="{{ route('customers.create')}}" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-plus"></i> Create New Customer</a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
              <table class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="customers-table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Customer Name</th>
                          <th>Created At</th>
                          <th>Updated At</th>
                          <th>Action</th>
                      </tr>
                  </thead>      
               </table>
            </div><!-- /.box-body -->
 </div>        
 @endsection

@push('scripts') 
<!-- DataTables -->
<script type="text/javascript" src="{{ asset('/plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/datatables/dataTables.bootstrap.js') }}"></script>
<!-- page script --> 
<script>
      $(function() {
    $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('customers.data') !!}'
        
    });
});

    </script> 
@endpush 