@extends('app')

@section('title', 'iPOS | View Customer')

@section('page_header')
  <h1> Customers <small>View Customer</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/customers">Customers</a></li>
    <li class="active">View Customer</li>
  </ol>
@endsection

@section('content')
<div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                @if($customer->avatar != null)
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/customers/'. $customer->avatar)}}" alt="User profile picture">
                @else
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/customers/default.png')}}" alt="User profile picture">
                @endif
                
                  <h3 class="profile-username text-center">{{ $customer->name }}</h3>
                  <p class="text-muted text-center">{{ $customer->job }}</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Invoiced</b> <a class="pull-right">Rs. {{ number_format($customer->sales()->sum('total'),2) }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Sales</b> <a class="pull-right">{{ $customer->sales()->count() }}</a>
                    </li>
                  </ul>

                  <a href="#" class="btn btn-danger btn-block"><b>Delete</b></a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Details</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <!--<strong><i class="fa fa-book margin-r-5"></i>  Education</strong>
                  <p class="text-muted">
                    B.S. in Computer Science from the University of Tennessee at Knoxville
                  </p>

                  <hr> -->

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                  <p class="text-muted">{{ $customer->address }}</p>

                  <hr>

                  <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                  <p class="text-muted">{{ $customer->tel }}</p>

                  <hr>

                  <strong><i class="fa  fa-mobile margin-r-5"></i> Mobile</strong>
                  <p class="text-muted">{{ $customer->mobile }}</p>

                  <!--<hr>

                  <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
                  <p>
                    <span class="label label-danger">UI Design</span>
                    <span class="label label-success">Coding</span>
                    <span class="label label-info">Javascript</span>
                    <span class="label label-warning">PHP</span>
                    <span class="label label-primary">Node.js</span>
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#sales" data-toggle="tab">Sales</a></li>
                 
                  <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" id="sales">
                    <table class="table">
                      <thead>
                        <th>Invoice No</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </thead>
                
                      <tbody>
                        @foreach($customer->sales as $sale)
                            <tr>
                                <th>SN{{ str_pad($sale->id , 7, '0', STR_PAD_LEFT) }}</th>
                                  <td>{{ number_format($sale->total,2) }}</td>
                                  <td>@if($sale->status == 0)
                                          <span class="label label-danger">Unpaid</span>
                                      @else
                                          <span class="label label-success">Paid</span>
                                      @endif
                                  </td>
                                  <td>{{ $sale->created_at }}</td>
                                  <td><a href="{{route('sales.show',$sale->id)}}" class="btn btn-default">View</a></td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                  {!! Form::model($customer,['route' => ['customers.update', $customer->id], 'method' => 'PUT','class'=>'form-horizontal']) !!}
                   
                      <div class="form-group">
                        {!! Form::label('name','Name',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::text('name',null,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        {!! Form::label('job','Job Position',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::text('job',null,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        {!! Form::label('address','Address',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::textarea('address',null,['size' => '30x2','class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        {!! Form::label('tel','Phone',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::text('tel',null,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                         {!! Form::label('mobile','Mobile',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::text('mobile',null,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    {!! Form::close() !!}
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div><!-- /.col -->
          </div><!-- /.row -->
@endsection