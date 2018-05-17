@extends('app')

@section('title', 'NNUTA | Add New Role')

@section('page_header')
  <h1> Roles <small>Add New Role</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Add New Role</li>
  </ol>
@endsection

@section('content')
	<div class="row">
  <div class="col-md-12">
     <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Role</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('route' => 'roles.store','class' => 'form-horizontal')) !!}
                  <div class="box-body">

                      <div class="form-group">
                      {!! Form::label('name','Role Name',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('display_name','Display Name',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('display_name',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('description','Description',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                        {!! Form::text('description',null,array('class'=>'form-control')) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('permissions','Permissions',array('class' => 'col-md-3 control-label')) !!}
                      <div class="col-md-9">
                              @foreach($permissions as $permission)
                                <input type="checkbox" name="permissions[]" value="{{$permission->id}}"> {{$permission->display_name}} </br>
                              @endforeach
                      </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    {!! Html::linkRoute('roles.index', 'Cancel', array(), array('class'=>'btn btn-danger')) !!}
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