@extends('layouts.app')

@section('title', 'iPOS | Edit Product')

@section('page_header')
  <h1> Products <small>Edit Product</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit Product</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Product</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($product,['route' => ['products.update', $product->id], 'method' => 'PUT','class' => 'form-horizontal']) !!}
                  <div class="box-body">

                    <div class="form-group">
                      {!! Form::label('product_name','Product Name',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('product_name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('uom_id','Unit of Measure',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                         {!! Form::select('uom_id', $uomOptions, null, ['class'=>'form-control']) !!}
                      </div>
                    </div>


                     <div class="form-group">
                      {!! Form::label('qty','Quantity',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('qty',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('cost','Cost',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('cost',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('sale','Sale Price',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                        {!! Form::text('sale',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('products.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
                    {!! Form::submit('Update',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection