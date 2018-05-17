@extends('app')

@section('title', 'iPOS | Purchase Report')

@section('style') 
   
@endsection

@section('page_header')
   <h1> Reports <small>Purchase Report</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Purchase Report</li>
  </ol>
@endsection

@section('content')
<div class="row no-print">
      <div class="col-md-12">
    <div class="box">
     <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#tab_1-1" data-toggle="tab">Annual Report</a></li>
              <li><a href="#tab_2-2" data-toggle="tab">Monthly Report</a></li>
              <li><a href="#tab_3-2" data-toggle="tab">Daily Report</a></li>
              <li class="pull-left header"><i class="fa fa-file-text "></i> Purchase Report</li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1-1">
              <div class="row">
                <!-- form start -->
                {!! Form::open(array('route' => 'reports.purchases','class' => 'form-horizontal', 'id' => 'annual')) !!}

                    <div class="col-md-9">
                       <div class="form-group">
                          {!! Form::label('year','Year',array('class' => 'col-sm-2 control-label')) !!}
                          <div class="col-sm-10">
                           {!! Form::selectRange('year', 2016, 2027,null,array('class'=>'form-control')) !!}
                          </div>
                        </div>
                    </div>

                     {!! Form::hidden('term','annual' , ['id' =>  'term', 'class'=>'form-control'])!!}    

                      <div class="col-md-3">
                        {!! Form::submit('Generate',array('class'=>'btn btn-primary pull-right')) !!}
                      </div>

                      {!! Form::close() !!}
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2-2">
                <div class="row">
                <!-- form start -->
                {!! Form::open(array('route' => 'reports.purchases','class' => 'form-horizontal', 'id' => 'monthly')) !!}

                    <div class="col-md-5">
                        <div class="form-group">
                          {!! Form::label('month','Month',array('class' => 'col-sm-2 control-label')) !!}
                          <div class="col-sm-10">
                            {!! Form::selectMonth('month',null,array('class'=>'form-control')) !!}
                          </div>
                        </div>
                      </div>

                    <div class="col-md-4">
                       <div class="form-group">
                          {!! Form::label('year','Year',array('class' => 'col-sm-2 control-label')) !!}
                          <div class="col-sm-10">
                           {!! Form::selectRange('year', 2016, 2027,null,array('class'=>'form-control')) !!}
                          </div>
                        </div>
                    </div>

                     {!! Form::hidden('term','monthly' , ['id' =>  'term', 'class'=>'form-control'])!!}    

                      <div class="col-md-3">
                        {!! Form::submit('Generate',array('class'=>'btn btn-primary pull-right')) !!}
                      </div>

                      {!! Form::close() !!}
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3-2">
               <div class="row">
                <!-- form start -->
                {!! Form::open(array('route' => 'reports.purchases','class' => 'form-horizontal', 'id' => 'daily')) !!}

                 <div class="col-md-9">   
                <div class="form-group">
                      {!! Form::label('trans_date','Date',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          {!! Form::text('trans_date',null,array('class'=>'form-control pull-right')) !!}
                        </div>
                      </div>
                    </div>
                    </div>


                     {!! Form::hidden('term','daily' , ['id' =>  'term', 'class'=>'form-control'])!!}    

                      <div class="col-md-3">
                        {!! Form::submit('Generate',array('class'=>'btn btn-primary pull-right')) !!}
                      </div>

                      {!! Form::close() !!}
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
                
</div><!-- /.box -->
</div>

 </div>     
    <!-- /.row -->

<div id="report_area"></div>
   
      

    
    
@endsection

@push('scripts')
<!-- bootstrap datepicker -->
  <script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- page script -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $(document).ajaxStart(function() { Pace.restart(); });

        $(function() {

            //Date picker
            $('#trans_date').datepicker({
              autoclose: true,
              format: 'yyyy-mm-dd'
            });
           
            $( "#annual" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('reports.purchases') !!}', $.param($(this).serializeArray()), function(data) {
                     $("#report_area").html(data);
              });
            });

            $( "#monthly" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('reports.purchases') !!}', $.param($(this).serializeArray()), function(data) {
                     $("#report_area").html(data);
              });
            });

             $( "#daily" ).submit(function( event ) {
              event.preventDefault();

              $.post('{!! route('reports.purchases') !!}', $.param($(this).serializeArray()), function(data) {
                     $("#report_area").html(data);
              });
            });
        });
    </script>
@endpush