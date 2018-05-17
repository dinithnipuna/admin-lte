@extends('layouts.app')

@section('title', 'iPOS | View Product')

@section('page_header')
  <h1> Products <small>View Product</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">View Product</li>
  </ol>
@endsection

@section('content')
	<div class="row">
      <div class="col-md-12">
          <div class="box">

                <div class="box-header with-border">
                  <h3 class="box-title">{{ $product->product_name }}</h3>
                </div><!-- /.box-header -->

                <div class="box-body">

                  <div class="col-md-8">
                    <p>Quantity : {{ $product->qty }}</p>
                    <p>Cost : {{ $product->cost }}</p>
                    <p>Selling Price : {{ $product->sale }}</p>
                    <p>Sales : {{ $product->sales()->count() }}</p>
                  </div>

                  <div class="col-md-4">

                    <div class="well">

                      <dl class="dl-horizontal">
                        <dt>Create At:</dt>
                        <dd>{{ date('M j, Y h:iA',strtotime($product->created_at)) }}</dd>
                        </dl>
                        <dl class="dl-horizontal">
                        <dt>Last Updated:</dt>
                        <dd>{{ date('M j, Y h:iA',strtotime($product->updated_at)) }}</dd>
                      </dl>

                      <hr />

                      <div class="row">

                        <div class="col-sm-6">
                        {!! Html::linkRoute('products.edit', 'Edit', array($product->id), array('class'=>'btn btn-primary btn-block')) !!}
                        </div>

                        <div class="col-sm-6">
                        {!! Form::model($product,['route' => ['products.destroy', $product->id], 'method' => 'DELETE']) !!}
                          {!! Form::submit('Delete',array('class'=>'btn btn-danger btn-block')) !!}
                        {!! Form::close() !!}
                        </div>

                      </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection