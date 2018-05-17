@extends('app')

@section('title', 'iPOS | View Supplier')

@section('page_header')
  <h1> Suppliers <small>View Supplier</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/suppliers">Suppliers</a></li>
    <li class="active">View Supplier</li>
  </ol>
@endsection

@section('content')
<div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                 @if($supplier->avatar != null)
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/suppliers/'. $supplier->avatar)}}" alt="User profile picture">
                @else
                      <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/suppliers/default.png')}}" alt="User profile picture">
                @endif
                  <h3 class="profile-username text-center">{{ $supplier->name }}</h3>
                  <p class="text-muted text-center">{{ $supplier->name }}</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Invoiced</b> <a class="pull-right">Rs. {{ number_format($supplier->purchases()->sum('total'),2) }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Purchases</b> <a class="pull-right">{{ $supplier->purchases()->count() }}</a>
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

                 <!-- <strong><i class="fa fa-book margin-r-5"></i>  Education</strong>
                  <p class="text-muted">
                    B.S. in Computer Science from the University of Tennessee at Knoxville
                  </p> 

                  <hr> -->

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                  <p class="text-muted">{{ $supplier->address }}</p>

                  <hr>

                  <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                  <p class="text-muted">{{ $supplier->tel }}</p>

                  <hr>

                  <strong><i class="fa  fa-mobile margin-r-5"></i> Mobile</strong>
                  <p class="text-muted">{{ $supplier->mobile }}</p>

                  <hr>

                 <!-- <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
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
                  <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" id="sales">
                    <table class="table">
                      <thead>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </thead>
                
                      <tbody>
                        @foreach( $supplier->purchases as $purchase)
                            <tr>
                                <th>PO{{ str_pad($purchase->id , 7, '0', STR_PAD_LEFT) }}</th>
                                  <td>Rs. {{ number_format($purchase->total,2) }}</td>
                                  <td>@if($purchase->status == 0)
                                          <span class="label label-danger">Unpaid</span>
                                      @else
                                          <span class="label label-success">Paid</span>
                                      @endif
                                  </td>
                                  <td>{{ $purchase->created_at }}</td>
                                  <td><a href="{{route('purchases.show',$purchase->id)}}" class="btn btn-default">View</a></td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <ul class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                      </li>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <li>
                        <i class="fa fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                          <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>
                          <div class="timeline-body">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                            quora plaxo ideeli hulu weebly balihoo...
                          </div>
                          <div class="timeline-footer">
                            <a class="btn btn-primary btn-xs">Read more</a>
                            <a class="btn btn-danger btn-xs">Delete</a>
                          </div>
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <li>
                        <i class="fa fa-user bg-aqua"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>
                          <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <li>
                        <i class="fa fa-comments bg-yellow"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>
                          <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                          <div class="timeline-body">
                            Take me to your leader!
                            Switzerland is small and neutral!
                            We are more like Germany, ambitious and misunderstood!
                          </div>
                          <div class="timeline-footer">
                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                          </div>
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <!-- timeline time label -->
                      <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                      </li>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <li>
                        <i class="fa fa-camera bg-purple"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>
                          <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
                          <div class="timeline-body">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                          </div>
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                      </li>
                    </ul>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                  {!! Form::model($supplier,['route' => ['customers.update', $supplier->id], 'method' => 'PUT','class'=>'form-horizontal']) !!}
                   
                      <div class="form-group">
                        {!! Form::label('name','Name',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {!! Form::text('name',null,['class'=>'form-control']) !!}
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