@extends('app')

@section('title', 'iPOS | Units of Measure')

@section('page_header')
  <h1> Products <small>Units of Measure</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Units of Measure</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('route' => 'uom.store','class' => 'form-horizontal')) !!}
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
                         <select class="form-control" name="category_id">
                                <option value=""></option>
                              @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                         </select>
                      </div>
                    </div>

                     <div class="form-group">
                      {!! Form::label('uom_type','Type',array('class' => 'col-sm-2 control-label')) !!}
                      <div class="col-sm-10">
                      <select class="form-control" name="uom_type" id="uom_type">  
                              <option value="false"></option>
                              <option value="bigger">Bigger than the reference Unit of Measure</option>
                              <option value="reference" selected>Reference Unit of Measure for this category</option>
                              <option value="smaller">Smaller than the reference Unit of Measure</option>        
                        </select>
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
                    {!! Form::submit('Create',array('class'=>'btn btn-info pull-right')) !!}
                  </div><!-- /.box-footer -->
                {!! Form::close() !!}
              </div><!-- /.box -->
</div>
</div>
@endsection

@push('scripts')
<!-- page script -->
<script type="text/javascript">
  $('#uom_type').change(function () {
    if($( this ).val() == "reference"){
      $('#factor').val('1.0');
    }else{
      $('#factor').val('');
    }
    });
</script>
@endpush