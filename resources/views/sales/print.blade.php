<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
.invoice{
  background-image: url(sagma_invoice.jpg);
  background-repeat: no-repeat;
  height: 14cm;
  width: 21.8cm;
  margin: auto;
  padding: 0px;
  position: relative;
}
.invoice p {
  line-height: normal;
  padding-bottom: 0px;
  margin-bottom: 0px;
  margin-top: 3px;
  padding-top: 5px;
}
.invoice .cus_details {
  position: absolute;
  top: 3.043cm;
  left: 3.704cm;
  height: auto;
  width: 375px;
  float: left;
}
.invoice .invoice_details {
  position: absolute;
  top: 3.043cm;
  width: 150px;
  float: right;
  left: 700px;
}
.invoice .invoice_header {
  position: absolute;
  width: 100%;
  float: left;
  top: 3cm;
  left: 0.529cm;
}
.invoice .products {
  width: 100%;
  position: absolute;
  top: 5.953cm;
  margin: 0px;
  padding: 0px;
  float: left;
  left: 0.529cm;
}
.invoice .invoice_footer {
  position: absolute;
  width: 100%;
  float: left;
  top: 11.5cm;
  width: 200px;
  left: 0.529cm;
}
body {
  margin: 0px;
  padding: 0px;
}
</style>
<script type="text/javascript">
  function doPrint() {
    window.print();            
    document.location.href = "{{ route('sales.create')}}"; 
}
</script>
</head>

<body onload="doPrint()">
<div class="invoice">

     <div class="invoice_header">
      <table width="805">
            <tr>
            <td colspan="3">&nbsp;</td>
            <td width="390">{{ $sale->customer->name }}</td>
            <td width="161" align="right">&nbsp;</td>
            <td width="123" align="right">SN{{ str_pad($sale->id , 7, '0', STR_PAD_LEFT)}}</td>
            </tr>
            
             <tr>
            <td colspan="3">&nbsp;</td>
            <td width="390">{{ $sale->customer->address }}</td>
            <td width="161" align="right">&nbsp;</td>
            <td width="123" align="right">{{ date('j - M - Y h:iA',strtotime($sale->created_at)) }}</td>
            </tr>
        </table>
        
    </div>
    
    <div class="products">
      <table width="805">
       @foreach($sale->products as $product)
            <tr valign="top">
              <td width="70">{{ $product->product_id }}</td>
              <td width="368">{{ $product->product_name }} {{ $product->warranty > 0 ? "( ".$product->warranty." Months Warranty )" : "" }}</td>
              <td width="70" align="center">{{ number_format($product->pivot->qty,3) }}</td>
              <td width="84" align="right">{{ number_format($product->pivot->amount,2) }}</td>
              <td width="77" align="right">{{ number_format($product->pivot->discount,2) }}</td>
              <td width="102" align="right">{{ number_format($product->pivot->sub_total,2) }}</td>
            </tr>
        @endforeach
         @if($sale->payment_type == 'card')
              <tr valign="top">
              <td width="70">&nbsp;</td>
              <td width="368">Card Payment Charges ({{ Settings::get('card_payment_charge') }}%)</td>
              <td width="70" align="center">&nbsp;</td>
              <td width="84" align="right">&nbsp;</td>
              <td width="77" align="right">&nbsp;</td>
              <td width="102" align="right">{{ number_format($sale->charges,2) }}</td>
             </tr>
          @endif
      </table>
    </div>
  <div class="invoice_footer">
      <table width="805">
        <tr>
        <td colspan="4"></td>
        <td width="77" align="right">{{ number_format($sale->total_discount,2) }}</td>
        <td width="102" align="right">{{ number_format($sale->total,2) }}</td>
        </tr>
        </table>
    </div>
</div>

</body>
</html>
