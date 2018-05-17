<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>

body {
	margin: 0px;
	padding: 0px;
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
		line-height: 18px;
}

#invoice{
	width:7.6cm;
	margin: auto;
}

</style>
<script type="text/javascript">
  function doPrint() {
    window.print();            
    //document.location.href = "{{ route('sales.create')}}"; 
}
</script>
</head>

<body onload="doPrint()">
<div id="invoice">
<div style="font-size:18px;"><center>SOUND OF SAGMA</center></div>
<center>No.28, Upper Floor,</center>
<center>New Market Complex, Nittambuwa.</center>
<center>Tel : 033 2287668 Mobile : 0777 565042</center>
<br/>
<div>Invoice No : SN{{ str_pad($sale->id , 7, '0', STR_PAD_LEFT)}}</div>
<div>Date : {{ date('j - M - Y h:iA',strtotime($sale->created_at)) }}</div>
<div>Cus. Name : {{ $sale->customer->name }}</div>
<br/>
<table style="width:7.6cm;">
<tr>
<td>Item</td>
<td>Unit Price</td>
<td align="right">Qty.</td>
<td align="right">Amount</td>
</tr>
<tr>
<td colspan="4"><hr/></td>
</tr>

 @foreach($sale->products as $product)
<tr>
<td colspan="4">{{ $product->product_name }} {{ $product->warranty > 0 ? "( ".$product->warranty." Months Warranty )" : "" }}</td>
</tr>
<tr>
<td>{{ $product->product_id }}</td>
<td>{{ number_format($product->pivot->amount,2) }}</td>
<td align="right">{{ number_format($product->pivot->qty,3) }}</td>
<td align="right">{{ number_format($product->pivot->sub_total,2) }}</td>
</tr>
 @endforeach


<tr>
<td colspan="4"><hr/></td>
</tr>

<tr>
<td colspan="3" align="right">Total Discount</td>
<td align="right">{{ number_format($sale->total_discount,2) }}</td>
</tr>

 @if($sale->payment_type == 'card')
<tr>
<td colspan="3" align="right">Card Payment Charges ({{ Settings::get('card_payment_charge') }}%)</td>
<td align="right">{{ number_format($sale->charges,2) }}</td>
</tr>
@endif

<tr>
<td colspan="3" align="right">Total Amount</td>
<td align="right">{{ number_format($sale->total,2) }}</td>
</tr>


</table>
<br/>
<center>EXCHANGE WITHING 4 DAYS WITH THE RECEIPT IN PERFECT CONDITION.</center>
<center>Thank You. Come Again !!.</center>
<br/>
<center>ATLA Solutions Software.</center>
</div>
</body>
</html>
