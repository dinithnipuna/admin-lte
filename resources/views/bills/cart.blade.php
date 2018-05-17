@extends('app')

@section('title', 'iPOS | View Invoice')

@section('page_header')
  <h1> Sales <small>View Invoice</small> </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/sales">Sales</a></li>
    <li class="active">View Invoice</li>
  </ol>
@endsection

@section('content')
  {{ basket | raw }}
@endsection

@push('scripts') 
  
<script type="text/javascript">
 $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

  $(document).ready(function() {

    $( "#payForm" ).submit(function( event ) {
   
      // Stop form from submitting normally
      event.preventDefault();
     
      var $form = $( this );

      var url = $form.attr( "action" );
     
      // Send the data using post
      var posting = $.post( url, $form.serialize() );
     
      // Put the results in a div
      posting.done(function( data ) {
         $('#dialog-confirm_complete').modal('hide');
         window.location = "{{ route('sales.index') }}";
      });
    }); 

   $( "#printReciept" ).click(function(event) {
      $.ajax({
                type: "POST",
                url: "{{ route('sales.reciept') }}",
                data: 'id='+{{$sale->id}} ,
                cache: false,
                success: function(data){
                  alert(data);
                  //document.location.href = "{{ route('sales.create')}}"; 
                }
              });   
       // Stop form from submitting normally
      event.preventDefault();
   });

  });
</script>

@endpush 