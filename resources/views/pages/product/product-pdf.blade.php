<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Title here -->
    <title>Product List</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="http://localhost/voucher/public/img/favicon/favicon.png">
  <style type="text/css">

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 710px;  
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: "Times New Roman", Times, serif;
  font-size: 14px; 
  
}

header {
 border-bottom: 1px solid #aaaaaa;
margin-bottom: 0;
padding-bottom: 5px;
padding-top: 5px;
}

#logo {
  float: left;
  margin-top: 8px;
}

#logo img {
  height: 70px;
}


#details {
  margin-bottom: 10px;
}

#client {
background: #eeeeee none repeat scroll 0 0;
border-left: 4px solid #0087c3;
font-size: 24px;
height: 27px;
margin-bottom: 15px;
text-align: center;
width: 99%;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  float: right;
  text-align: right;
}

#invoice h4 {
  color: #0087C3;
  font-size: 21px;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 11px;
  color: #777777;
}

.invoice-list {
  font-family: "Times New Roman", Times, serif;
  font-size: 13px; 
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;

  height: 520px;

}

.invoice-list .voucher_head{
	border: 1px solid;
}
.invoice-list ul{
  margin: 0;
  padding: 0;
}

.invoice-list ul li{
  display: inline-block;
  margin-right: -2px;
  color: #000000; 
  

}

.invoice-list .voucher_head li{
  border-right: 1px solid #000;
     
  text-align: center;
}


.invoice-list .voucher_head  li:last-child {
  border-right: none;
}


.profit-list ul{
  text-align: right;
}
.profit-list ul li{
    
     display: inline;
    font-size: 15px;
    font-weight: bold;
    padding-left: 27px;
    padding-right: 25px;
     
}

.header_left{
  width: 400px; 
  display: inline-block;
  text-align: left; 
}

.header_right {
    display: inline-block;
    text-align: right;
    vertical-align: top;
    width: 200px;
}

.content_left{
  width: 300px; 
  display: inline-block;
  text-align: left;
}

.content_left h1{

}

.content_left p{
margin-top: 10px;
}

.content_right {
    display: inline-block;
padding: 9px 0 0;
text-align: right;
vertical-align: top;
width: 406px;
}
.content_right p {
    line-height: 18px;
    margin: 0;
}

.content_left p span {
    display: inline-block;
    font-weight: bold;
    margin-bottom: 5px;
}

.thanks_text{
  text-align: right; 
  padding-right: 150px; 
}

.office_address {
    margin-left: 370px;
}
.invoice_footer {
  margin-top: 40px;
}
.amounts_in_word {
  height: 50px;
margin-bottom: 10px;
margin-top: 10px;
padding: 0;
}
.total_block ul {
  margin: 0;
  padding: 0;
}
.total_block ul li{
	border: 1px solid;
color: #000000;
display: inline-block;
list-style: outside none none;
margin-right: -5px; 
}

  </style>
  <body>

    <main>
      <div id="details" class="clearfix">
        <div class="content_left">
          <p><span>ISHO</span> <br/>Banani, <br/>Dhaka</p>
        </div>

        <div class="content_right">
          <p><span>Date:</span> {{$today}}</p> 
        </div>
      </div>
      
      <div class="invoice-list">
      
        <ul class="">
           <li  style="width:100px; font-weight: bold;text-align:center;font-weight:bold;">No</li>
           <li  style="width:325px; font-weight: bold;text-align:center;font-weight:bold;">Name</li>
           <li  style="width:135px; font-weight: bold;text-align:center;font-weight:bold;">Brand</li>
           <li  style="width:135px; font-weight: bold;text-align:center;font-weight:bold;">Total</li>
         </ul>

        @if(!empty($all_content) && count($all_content) > 0)
	        @foreach($all_content as $key => $product)
	            @php
	                $total_product = \DB::table('stock_tbl')->where('product_id', $product->id)->where('stock_status', '1')->sum('num_of_product');
	            @endphp
		         <ul class="voucher_content">
		           <li  style="width:100px;text-align:center;">{{($key+1)}}</li>
		           <li  style="width:325px;text-align:center;">{{$product->product_name}}</li>
		           <li  style="width:135px;text-align:center;">{{$product->brand}}</li>
		           <li  style="width:135px;text-align:center;">{{$total_product}}</li>
		        </ul>
	        @endforeach
        @endif

      </div>	 
    </main>
    
  </body>
</html>