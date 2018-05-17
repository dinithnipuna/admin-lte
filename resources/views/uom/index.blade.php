@extends('layouts.app')

@section('title', 'iPOS | Units of Measure')

@section('style') 

@endsection

@section('page_header')
  <h1> Products <small>Units of Measure</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/products">Products</a></li>
    <li class="active">Units of Measure</li>
  </ol>
@endsection

@section('content')
 <div class="row">
        <div class="col-xs-12">
  <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="col-md-10">
                    <a href="{{ route('uom.create')}}" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-plus"></i> Add New UOM</a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Units of Measure</th>
                <th>Unit of Measure Category</th>
                <th>Action</th>
            </tr>
            @foreach($uoms as $uom)
              <tr>
                <td>{{ $uom->id }}</td>
                <td>{{ $uom->name }}</td>
                <td>{{ $uom->category->name }}</td>
                <td>
                    <a href="{{ route('uom.edit', $uom->id) }}" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <button type="button" class="btn btn-danger" v-on:click="destroy({{ $uom->id }})"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                </td>
              </tr>
            @endforeach
        </thead>
    </table>
                  
    </div><!-- /.box-body -->
 </div>   
 </div>
 </div>                       
@endsection

@section('scripts')
  <script>

    var app = new Vue({
      el: '#app',
      methods: {
        destroy: function (id) {
          alert(id);
        }
      }
    });

  </script>
@endsection