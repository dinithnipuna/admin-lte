@extends('app')

@section('title', 'iPOS | Edit UOM')

@section('page_header')
  <h1> Products <small>Edit UOM</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit UOM</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit UOM</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($uom,['route' => ['uom.update', $uom->id], 'method' => 'PUT','class' => 'form-horizontal']) !!}
                  <div class="box-body">

                    <div class="form-group">
                      {!! Form::label('name','Unit of Measure',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('category_id','Unit of Measure Category',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                         {!! Form::select('category_id', $categories, null, ['class'=>'form-control']) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('uom_type','Type',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      {!! Form::select('uom_type', $types, null, ['class'=>'form-control']) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('factor','Ratio',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('factor',null,array('class'=>'form-control', 'id' => 'factor')) !!}
                      </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('uom.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Update',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection