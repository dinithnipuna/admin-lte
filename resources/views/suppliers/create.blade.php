@extends('app')

@section('title', 'iPOS | Add New Supplier')

@section('page_header')
  <h1> Suppliers <small>Add New Supplier</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/suppliers">Suppliers</a></li>
    <li class="active">Add New Supplier</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Supplier</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('route' => 'suppliers.store','class' => 'form-horizontal')) !!}
                  <div class="box-body">
                  <div class="col-md-8">

                    <div class="form-group">
                      {!! Form::label('name','Supplier Name',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                       <div class="input-group">
                     <div class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </div>
                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('address','Address',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <div class="input-group">
                     <div class="input-group-addon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                        {!! Form::text('address',null,array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('tel','Telephone No',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                        {!! Form::text('tel',null,array('class'=>'form-control', 'data-inputmask'=>'"mask": "999 9999999"', 'data-mask' => '' )) !!}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('mobile','Mobile',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                       <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-mobile"></i>
                  </div>
                        {!! Form::text('mobile',null,array('class'=>'form-control','data-inputmask'=>'"mask": "999 9999999"', 'data-mask' => '' )) !!}
                        </div>
                      </div>
                    </div>

                    </div>

                    <div class="col-md-4">
                      <div class="well">
                      {!! Form::label('avatar','Upload Avatar :') !!}
                          <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/customers/default.png')}}" alt="User profile picture">
                          {!! Form::file('avatar') !!}
                      </div>

                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('suppliers.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Create',array('class'=>'btn btn-primary pull-right')) !!}
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
      //Telephone Number Mask
      $("[data-mask]").inputmask();
  });
</script> 
@endpush 