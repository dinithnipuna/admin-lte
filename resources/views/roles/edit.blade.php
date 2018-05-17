@extends('app')

@section('title', 'NNUTA | Edit Role')

@section('page_header')
  <h1> Roles <small>Edit Role</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit Role</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Role</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($role,['route' => ['roles.update', $role->id], 'method' => 'PUT','class' => 'form-horizontal']) !!}
                  <div class="box-body">

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

                    <div class="form-group">
                      {!! Form::label('permissions','Permissions',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                              @foreach($permissions as $permission)
                                <input type="checkbox" {{in_array($permission->id,$role_permissions)? "checked" : ""}} name="permissions[]" value="{{$permission->id}}"> {{$permission->display_name}} </br>
                              @endforeach
                      </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('roles.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Update',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection