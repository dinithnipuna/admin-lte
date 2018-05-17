@extends('app')

@section('style')
<link rel="stylesheet"href="//codeorigin.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
@endsection

@section('content') 

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> Sales <small>Create New Invoice</small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Sales</a></li>
    <li class="active">Invoice</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-md-9">
      <div class="box">
        <div class="box-header">
          <div class="form-group col-md-9">
            <input type="text" class="form-control" id="product" name="product" placeholder="Enter product name or id">
          </div>
          <div class="form-group col-md-3">
            <button class="btn btn-primary">New Product</button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered" id="product-table">
            <thead>
              <tr>
                <th></th>
                <th>Product Name</th>
                <th>Product ID</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Disc %</th>
                <th>Total</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xs-6 col-md-3">
      <div class="box">
        <div class="box-header">
          <h5>Select Customer (Optional) </h5>
          <div class="form-group">
            <input type="text" class="form-control" id="product" name="product" placeholder="Enter product name or id">
          </div>
          OR
          <button class="btn btn-primary">New Customer</button>
        </div>
        <div class="box-body"> </div>
      </div>
      <div class="box box-solid">
        <div class="box-body">
          <table id="sales_items" class="table">
            <tbody>
              <tr class="warning">
                <td class="left">Items In Cart:</td>
                <td class="right"><div id="cart_count">0</div></td>
              </tr>
              <tr class="info">
                <td class="left">Sub Total:</td>
                <td class="right"><div id="sub_total">Rs. 0.00</div></td>
              </tr>
              <tr class="color1">
                <td class="left"> 07% Sales Tax:</td>
                <td class="right">Rs. 0.00</td>
              </tr>
              <tr class="success">
                <td><h5 class="sales_totals">Total:</h5></td>
                <td><h5 class="currency_totals">Rs. 0.00</h5></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body --> 
      </div>
      <div class="box box-solid">
        <div class="box-body">
          <table id="amount_due" class="table">
            <tbody>
              <tr class="error">
                <td><h4 class="sales_amount_due">Amount Due:</h4></td>
                <td><h4 class="amount_dues">$0.49</h4></td>
              </tr>
            </tbody>
          </table>
          <div id="make_payment">
            <form action="https://demo.phppointofsale.com/index.php/sales/add_payment" method="post" accept-charset="utf-8" id="add_payment_form" autocomplete="off">
              <table id="make_payment_table" class="table">
                <tbody>
                  <tr id="mpt_top">
                    <td id="add_payment_text"><label accesskey="y" for="payment_types">Add Pa<em>y</em>ment:</label></td>
                    <td><select name="payment_type" id="payment_types" class="input-medium">
                        <option value="Cash" selected="selected">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Gift Card">Gift Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Booty">Booty</option>
                      </select></td>
                  </tr>
                  <tr id="mpt_bottom">
                    <td id="tender" colspan="2"><div class="input-append">
                        <input name="amount_tendered" value="0.49" id="amount_tendered" class="input-medium input_mediums" accesskey="p" type="text">
                        <input class="btn btn-primary" id="add_payment_button" value="Add Payment" type="button">
                      </div></td>
                  </tr>
                </tbody>
              </table>
            </form>
          </div>
          <label id="comment_label" for="comment">C<em>o</em>mments:</label>
          <br>
          <textarea class="" name="comment" rows="4" id="comment" accesskey="o"></textarea>
          <br>
          <label id="show_comment_on_receipt_label" for="show_comment_on_receipt" class="checkbox">Show comments on receipt<input name="show_comment_on_receipt" value="1" checked="checked" id="show_comment_on_receipt" type="checkbox"></label>  		
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="row">
    <div class="col-md-8">.col-md-8</div>
    <div class="col-md-4">.col-md-4</div>
  </div>
</section>
@endsection 

@push('scripts') 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script src="//codeorigin.jquery.com/ui/1.10.2/jquery-ui.min.js"></script> 
<!-- DataTables --> 
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script> 
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script> 
<script src="{{ asset('/vendor/datatables/buttons.server-side.js') }}"></script> 
<!-- page script --> 
<script>
$(function()
{
	
	
	var sub_total=0;
	var cart_count=0;
	var product_array = Array();
	var row_index = 0;
	
	function product(){
    // Properties
    this.id;
    this.qty = 0;
    // Methods
    this.takeDamage = function(amount){
        this.hitpoints -= amount;
    };
}
	
	function reload_data(sub_total,cart) {
    	    $('#cart_count').text(product_array.length);
			$('#sub_total').text(sub_total);
	}
	
	var table= $('#product-table').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
		"searching": false
    });
	
	 $( "#product" ).autocomplete({
	  source: "autocomplete",
	  minLength: 3,
	 focus: function( event, ui ) {
        $( "#product" ).val( ui.item.name );
        return false;
      },
	  select: function(event, ui) {
	  	$('#product').val(ui.item.name);
		$.each(product_array, function() {
			if (this.id == ui.item.product_id) {
				var old_qty = this.qty;
				this.qty = qty;
				var row = this.index;
				cart_count = cart_count + ( qty - old_qty) ;
				sub_total = sub_total + price *  ( qty - old_qty);
				reload_data(sub_total, cart_count)
					table.cell( this.index, 5 ).data( qty ).draw();
		} else {
		var p = new product();
		p.index = row_index ;
		p.id = ui.item.product_id;
		p.qty = 1;
		product_array.push(p);
		
		 table.row.add( [
		    '<a class="delete_item" ><i class="fa fa-trash-o fa fa-2x text-error"></i></a>',
			ui.item.value,
            ui.item.product_id,
            ui.item.qty,
            '<input class="price" name="row-1-age" value="'+ui.item.sale+'" type="text" size="7">',
			'<input class="qty" name="qty" value="1" type="text" size="7">',
			'<input class="dis" name="row-1-age" value="0" type="text" size="7">',
			'<input class="total" name="row-1-age" value="'+ui.item.sale+'" type="text" size="7">'

        ] ).draw( false );
		sub_total = sub_total + ui.item.sale;
		cart_count ++
		reload_data(sub_total,cart_count)
		row_index ++;
		
	  }
		});
		
		
		
		 return false;
	  }
	});
	
	$('#product-table tbody').on('click', '.delete_item', function () {
		var tr = $(this).closest('tr');
        
		var qty = tr.find('.qty').val();
		var price = tr.find('.price').val();
		var dis = tr.find('.dis').val();
		var total = price * qty;
		sub_total = sub_total - total;
		cart_count = cart_count - qty ;
		reload_data(sub_total,cart_count)
		
		var rowData = table.row(tr).data();
		var id = rowData[2];
		
		$.each(product_array, function() {
			if (this.id == id) {
				var old_qty = this.qty;
				this.qty = qty;
				cart_count = cart_count + ( qty - old_qty) ;
				sub_total = sub_total + price *  ( qty - old_qty);
				var val = product_array.indexOf(this);
				//alert(val);
				var val=product_array.splice( val, 1 );
				reload_data(sub_total, cart_count);
				
			}
		});
		
		table.row( tr ).remove().draw( false );
	});
	
	$('#product-table tbody').delegate('.qty,.dis,.price', 'change', function () {
		var tr = $(this).closest('tr');
        var qty = tr.find('.qty').val();
		var price = tr.find('.price').val();
		var dis = tr.find('.dis').val();
		var total = price * qty;
		reload_data(sub_total,cart_count);
		tr.find('.total').val(total);
		var rowData = table.row(tr).data();
		var id = rowData[2];
		$.each(product_array, function() {
			if (this.id == id) {
				var old_qty = this.qty;
				this.qty = qty;
				cart_count = cart_count + ( qty - old_qty) ;
				sub_total = sub_total + price *  ( qty - old_qty);
				reload_data(sub_total, cart_count);
			}
		});
		
	});
});
    </script> 
@endpush 