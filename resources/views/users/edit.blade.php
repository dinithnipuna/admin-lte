@extends('app')

@section('title', 'iPOS | Edit User')

@section('page_header')
  <h1> User <small>Edit User</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit User</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit User</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($user,['route' => ['users.update', $user->id], 'method' => 'PUT','class' => 'form-horizontal', 'files' => true]) !!}
                  <div class="box-body">
                    <div class="col-md-8">

                     <div class="form-group">
                      {!! Form::label('role','Role',array('class' => 'col-sm-2 control-label')) !!}
                       <div class="col-sm-10">
                         {!! Form::select('role', $roleOptions, null, ['class'=>'form-control']) !!}
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
                     {!! Form::label('avatar','Upload Avatar :') !!}
                           @if($user->avatar != null)
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/members/'. $member->avatar)}}" alt="User profile picture">
                @else
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/members/default.png')}}" alt="User profile picture">
                @endif
                <hr/>
                          {!! Form::file('avatar') !!}
                      </div>

                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('users.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Update',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection

@push('scripts') 
<!-- InputMask -->
<script src="{{ asset('/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<!-- page script --> 
<script>
      $(function() {
    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();
});

    </script> 
@endpush 