@extends('app')

@section('title', 'NNUTA | List Of Roles')

@section('style') 
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('page_header')
  <h1> Users <small>List Of Roles</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">List Of Roles</li>
  </ol>
@endsection

@section('content')
 <div class="row">
        <div class="col-xs-12">
  <div class="box">
                <div class="box-header with-border">
                  <div class="col-md-10">
                   <a href="{{ route('roles.create')}}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>Add New Role</a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-bordered table-hover" id="roles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Display Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
                  
    </div><!-- /.box-body -->
 </div>   
 </div>
 </div> 

 <!-- Add Role Modal -->

<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

     {!! Form::open(array('route' => 'roles.store','class' => 'form-horizontal','id' => 'addForm')) !!}

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Create New Role</h4>

      </div>

      <div class="modal-body">

                     <div class="form-group">

                      {!! Form::label('name','Role Name',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('name',null,array('class'=>'form-control')) !!}

                      </div>

                    </div>

                    <div class="form-group">

                      {!! Form::label('display_name','Display Name',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('display_name',null,array('class'=>'form-control')) !!}

                      </div>

                    </div>

                    <div class="form-group">

                      {!! Form::label('description','Description',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('description',null,array('class'=>'form-control')) !!}

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

 <!-- Edit Role Modal -->

<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

     {!! Form::open(array('route' => 'roles.store','class' => 'form-horizontal','id' => 'editForm')) !!}

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Create New Role</h4>

      </div>

      <div class="modal-body">
                     {!! Form::hidden('role_id', null , array('id' => 'role_id')) !!}  
                     <div class="form-group">

                      {!! Form::label('name','Role Name',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('name',null,array('class'=>'form-control')) !!}

                      </div>

                    </div>

                    <div class="form-group">

                      {!! Form::label('display_name','Display Name',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('display_name',null,array('class'=>'form-control')) !!}

                      </div>

                    </div>

                    <div class="form-group">

                      {!! Form::label('description','Description',array('class' => 'col-md-3 control-label')) !!}

                      <div class="col-md-9">

                        {!! Form::text('description',null,array('class'=>'form-control')) !!}

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

<!-- Add Permissions To Role Modal -->

<div class="modal fade" id="addPermissionsToRoleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

     {!! Form::open(array('route' => 'roles.store','class' => 'form-horizontal','id' => 'addPermissionsForm')) !!}

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Add Permissions To Role</h4>

      </div>

      <div class="modal-body">

                     <div id="permission_list">

                    </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

         {!! Form::submit('Save',array('class'=>'btn btn-info pull-right')) !!}

      </div>

       {!! Form::close() !!}

    </div>

  </div>

</div>

@endsection

@push('scripts')
    <!-- DataTables -->
        <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script> 
    <!-- page script -->
    <script type="text/javascript">

        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $(function() {
            var oTable = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('roles.data') !!}'
            });

            $( "#addForm" ).submit(function( event ) {
              event.preventDefault();
              $.post('{!! route('roles.store') !!}', $.param($(this).serializeArray()), function(data) {
                    $('#addRoleModal').modal('hide'); 
                    oTable.ajax.reload();
              });
            });

            $('#editRoleModal').on('shown.bs.modal', function (event) {

                var modal = $(this);

                var button = $(event.relatedTarget) // Button that triggered the modal

                var roleid = button.data('roleid') // Extract info from data-* attributes

                  $.get('{!! route('roles.edit') !!}', {id:roleid}, function(data) {

                          modal.find('.modal-body #role_id').val(data.id);

                          modal.find('.modal-body #name').val(data.name);

                          modal.find('.modal-body #display_name').val(data.display_name);

                          modal.find('.modal-body #description').val(data.description);

                  });

            });

            $( "#editForm" ).submit(function( event ) {

              event.preventDefault();

              $.post('{!! route('roles.update') !!}', $.param($(this).serializeArray()), function(data) {

                    $('#editRoleModal').modal('hide');   
                    oTable.ajax.reload();
              });

            });

            $('#addPermissionsToRoleModal').on('shown.bs.modal', function (event) {

                var modal = $(this);

                var button = $(event.relatedTarget) // Button that triggered the modal

                var roleid = button.data('roleid') // Extract info from data-* attributes

                  $.get('{!! route('roles.getPermissions') !!}', {id:roleid}, function(data) {

                          modal.find('.modal-body #permission_list').html(data);

                  });

            });

            $( "#addPermissionsForm" ).submit(function( event ) {

              event.preventDefault();

              $.post('{!! route('roles.setPermissions') !!}', $.param($(this).serializeArray()), function(data) {

                    $('#addPermissionsToRoleModal').modal('hide');   
                    //oTable.ajax.reload();
              });

            });

        });
    </script>
@endpush