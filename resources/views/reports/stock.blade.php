@extends('app')

@section('title', Settings::get('business_name').' | '.trans('header.stock_valuation'))

@section('style') 
    {!! Charts::assets() !!}
@endsection

@section('page_header')
  <h1> {{ trans('header.reports') }} <small>{{ trans('header.stock_valuation') }}</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> {{ trans('header.home') }}</a></li>
    <li class="active">{{ trans('header.stock_valuation') }}</li>
  </ol>
@endsection

@section('content')
<div class="row">
        <div class="col-md-12">
 <!-- BAR CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('header.stock_valuation') }}</h3>
            </div>
            <div class="box-body">
            <!-- Info boxes -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-plus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Stock Value</span>
              <span class="info-box-number">Rs. {{ number_format($total, 2, '.', ',') }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-refresh"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Reorder Level Products</span>
              <span class="info-box-number">{{ $rop_products }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Out Of Stock Products</span>
              <span class="info-box-number">{{ $oos_products }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->       
              {!! $report !!}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </div>
          </div>
@endsection