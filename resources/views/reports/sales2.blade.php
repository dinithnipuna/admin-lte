@extends('app')

@section('title', 'iPOS |  Sales Analysis')

@section('style') 
    {!! Charts::assets() !!}
@endsection

@section('page_header')
  <h1> Reports <small>Sales Analysis</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Sales Analysis</li>
  </ol>
@endsection

@section('content')
<div class="row">
        <div class="col-md-12">
 <!-- BAR CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Sales Analysis</h3>

              {{-- <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div> --}}
            </div>
            <div class="box-body">
                 {!! $chart->render() !!}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </div>
          </div>
@endsection