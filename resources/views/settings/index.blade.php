@extends('app')

@section('title', 'iPOS | Configuration')

@section('style') 
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="{{ asset('/dist/css/toastr.min.css') }}">
@endsection

@section('page_header')
<h1>Configuration <small>Business Details</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Configuration</li>
                    </ol>
@endsection                    

@section('content')
<div class="row">
  <div class="col-md-12">
     <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Business Details</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('route' => 'settings.store','class' => 'form-horizontal','files' => true, 'id'=>'target')) !!}
                  <div class="box-body">
                    <div class="col-md-9">
                    <div class="form-group">
                      {!! Form::label('business_name','Business Name',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                       <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-globe"></i>
                        </div>
                        {!! Form::text('business_name',Settings::get('business_name'),array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('business_address_1','Address Line 1',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                        {!! Form::text('business_address_1',Settings::get('business_address_1'),array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('business_address_2','Address Line 2',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                        {!! Form::text('business_address_2',Settings::get('business_address_2'),array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('land_phone','Land Phone',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-phone"></i>
                        </div>
                          {!! Form::text('land_phone',Settings::get('land_phone'),array('class'=>'form-control', 'data-inputmask'=>'"mask": "999 9999999"', 'data-mask')) !!}
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
                        {!! Form::text('mobile',Settings::get('mobile'),array('class'=>'form-control', 'data-inputmask'=>'"mask": "999 9999999"', 'data-mask')) !!}
                      </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('card_payment_charge','Card Payment Charge',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                          {!! Form::text('card_payment_charge',Settings::get('card_payment_charge'),array('class'=>'form-control')) !!}
                          <span class="input-group-addon">%</span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('notice','Receipt Notice',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::textarea('notice',Settings::get('notice'),array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('message','Receipt Message',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-commenting"></i>
                        </div>
                        {!! Form::text('message',Settings::get('message'),array('class'=>'form-control')) !!}
                      </div>
                      </div>
                    </div>

                    </div>

                     <div class="col-md-3">
                      <div class="well">
                           {!! Form::label('business_logo','Upload Business Logo :') !!}
                           @if(Settings::get('business_logo') != null)
                                  <img class="img-responsive" width="256" src="{{ asset('images/business/'. Settings::get('business_logo'))}}" alt="User profile picture">
                           @else
                                  <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/business/default.png')}}" alt="User profile picture">
                           @endif
                          {!!Form::checkbox('delete_logo', 'yes') !!} Delete Business Logo
                           <hr>
                          {!! Form::file('business_logo') !!}
                      </div>

                       <div class="well">
                         {!! Form::label('business_logo_mini','Upload Business Logo Mini :') !!}
                           @if(Settings::get('business_logo_mini') != null)
                                  <img class="img-responsive" width="64" src="{{ asset('images/business/'. Settings::get('business_logo_mini'))}}" alt="User profile picture">
                           @else
                                  <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/business/default.png')}}" alt="User profile picture">
                           @endif
                           {!!Form::checkbox('delete_logo_mini', 'yes') !!} Delete Business Logo Mini
                           <hr>
                          {!! Form::file('business_logo_mini') !!}
                      </div>

                      <div class="well">
                         {!! Form::label('bg_image','Upload Bacground Image :') !!}
                           @if(Settings::get('bg_image') != null)
                                  <img class="img-responsive" width="64" src="{{ asset('images/business/'. Settings::get('bg_image'))}}" alt="User profile picture">
                           @else
                                  <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/business/default.png')}}" alt="User profile picture">
                           @endif
                           {!!Form::checkbox('delete_bg_image', 'yes') !!} Delete Bacground Image
                           <hr>
                          {!! Form::file('bg_image') !!}
                      </div>

                    </div>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Form::submit('Save Changes',array('class'=>'btn btn-success btn-lg')) !!}
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
<!-- Toasrt -->
<script src="{{ asset('/dist/js/toastr.min.js') }}"></script> 
  
<script type="text/javascript">
$(document).ajaxStart(function() { Pace.restart(); });

$(function () {
    $("[data-mask]").inputmask();

    $( "#target" ).submit(function( event ) {
      event.preventDefault();

      $.ajax( {
      url: '{!! route('settings.store') !!}',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,
        success: function(data){ 
                 toastr.success(data);   
        }
      });

    });
 });
</script>

@endpush 