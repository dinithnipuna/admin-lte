@extends('app')

@section('title', 'iPOS | List Of Purchases')

@section('style') 
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
<style type="text/css" class="init">
  table.dataTable th,
  table.dataTable td {
    white-space: nowrap;
  }
  </style>
  <link rel="stylesheet" href="{{ asset('/dist/css/toastr.min.css') }}">
@endsection

@section('page_header') 
  <h1> Purchases <small>List Of Purchases</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">List Of Purchases</li>
  </ol>
@endsection

@section('content') 
<div class="box box-primary">
                <div class="box-header with-border">
                  <div class="col-md-10">
                    <a href="{{ route('purchases.create')}}" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-plus"></i> Create New Purchase</a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="purchases-table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Supplier</th>
                          <th>User</th>
                          <th>Total</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Action</th>
                      </tr>
                  </thead>      
               </table>
            </div><!-- /.box-body -->
 </div>    

 <!-- Purchase Delete Modal -->
<div class="modal fade" id="dialog-confirm_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     {!! Form::open(['route' => ['purchases.destroy'], 'method' => 'DELETE', 'id' => 'deleteForm'])!!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Deletion</h4>
      </div>
      <div class="modal-body">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete this ? This cannot be undone.</p>
               {!! Form::hidden('purchase_id',null , ['id' =>  'purchase_id', 'class'=>'form-control'])!!}        
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

<script type="text/javascript" src="{{ asset('/plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/datatables/dataTables.bootstrap.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('/dist/js/toastr.min.js') }}"></script> 
<!-- page script --> 
<script>
$(document).ready(function() {

    var oTable = $('#purchases-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('purchases.data') !!}',
        order: [ [0, 'desc'] ]
    });

    $('#dialog-confirm_delete').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      $('#purchase_id').val(recipient);


    });

    $( "#deleteForm" ).submit(function( event ) {
   
      // Stop form from submitting normally
      event.preventDefault();
     
      var $form = $( this );

      var url = $form.attr( "action" );
     
      // Send the data using post
      var posting = $.post( url, $form.serialize() );
     
      // Put the results in a div
      posting.done(function( data ) {
         $('#dialog-confirm_delete').modal('hide');
         oTable.ajax.reload();
         toastr.success(data);  
      });
    }); 

});

</script> 
@endpush 