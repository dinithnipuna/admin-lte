@extends('app')

@section('title', 'iPOS | Add New Member')

@section('page_header')
  <h1> Members <small>Add New Member</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add New Member</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Member</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('route' => 'users.store','class' => 'form-horizontal','files' => true)) !!}
                  <div class="box-body">
                   <div class="col-md-8">

                   <div class="form-group">
                      {!! Form::label('role','Role',array('class' => 'col-sm-2 control-label')) !!}
                       <div class="col-sm-10">
                          <select class="form-control" name="role">
                              @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->display_name}}</option>
                              @endforeach
                          </select>
                    </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('email','Email',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::email('email',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('first_name','First Name',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('first_name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('last_name','Last Name',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('last_name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('password','Password',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::password('password',array('class'=>'form-control')) !!}
                      </div>
                    </div>

                   <div class="form-group">
                      {!! Form::label('password_confirmation','Password Confirmation',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::password('password_confirmation',array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    </div>

                      <div class="col-md-4">
                      <div class="well">
                          <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/members/default.png')}}" alt="User profile picture">
                          {!! Form::file('avatar') !!}
                      </div>

                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('users.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Create',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection