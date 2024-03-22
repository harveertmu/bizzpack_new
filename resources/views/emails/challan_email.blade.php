<!doctype html>

<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BigStore: Shopping Invoice</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.min.css">

</head>
<style>
  .customercard {
    min-width: 65%;
  }

  .itemscard {
    min-width: 98.5%;
    margin-left: 0.5%;
  }

  .ui.card {
    width: 100%;
  }
</style>

<body translate="no">
  <div class="container invoice">
    <div class="invoice-header">
      <div class="ui left aligned grid">
      <table class="ui celled table">
         
         <tbody>
           <tr>
             <td>
         <div class="left floated left aligned six wide column">
         <div class="ui">
           <h1 class="ui header pageTitle">DELIVERY <small class="ui sub header">CHALLAN</small></h1>
           <h4 class="ui sub header invDetails">Delivery Note No: 23-24/orderID-{{$oid}}</h4>
         </div>
         </div>
           
              </td>
             <td>
       <div class="right floated left aligned six wide column">
           <div class="ui">
             <div class="column two wide right floated">
             <ul class="">
               <li><strong>ORIGINAL COPY</strong></li>

             </ul>
             </div>
           </div>
           </div>
             </td>
      
            
 </tfoot>
       </table>
      </div>
    </div>
    <div class="ui segment cards">



      <table class="">

        <tbody>
          <tr>
            <td>
              <div class="ui card">
                <div class="content">
                  <div class="header">Company Details</div>
                </div>
                <div class="content">
                  <ul>
                  <li> <strong> Name: {{$supplier['company_name']}} </strong> </li>
                  <li><strong> Address: </strong>{{$supplier['street_address']}} {{$supplier['address_apt']}} {{$supplier['city']}}</li>
                  <li> <strong> State Name: </strong> {{$supplier['state']}} </li>
                  <li> <strong> Zip:</strong> {{$supplier['zip']}} </li>
                  <li><strong> Phone: </strong> {{$supplier['phone_number']}} </li>
                  <li><strong> Email: </strong> {{$supplier['email']}} </li>
                  <li><strong> GSTIN/UIN: </strong> {{$supplier['gst_number']}} </li>
                  </ul>
                </div>
              </div>

            </td>
            <td>

              <div class="ui card">
                <div class="content">
                  <div class="header">Customer Details</div>
                </div>
                <div class="content">
                  <ul>
                    
                  <li> <strong> Name: {{$customer['company_name']}} </strong> </li>
                  <li><strong> Address: </strong>{{$customer['street_address']}} {{$customer['address_apt']}} {{$customer['city']}}</li>
                  <li> <strong> State Name: </strong> {{$customer['state']}} </li>
                  <li> <strong> Zip:</strong> {{$customer['zip']}} </li>
                  <li><strong> Phone: </strong> {{$customer['phone_number']}} </li>
                  <li><strong> Email: </strong> {{$customer['email']}} </li>
                  <li><strong> GSTIN/UIN: </strong> {{$customer['gst_number']}} </li>
                  </ul>
                </div>
              </div>
            </td>
            <td>
              <div class="ui card">
                <div class="content">
                  <div class="header">Other Details</div>
                </div>
                <div class="content">
                  <ul>
                  <li> <strong> Dated: {{ date('d-m-Y') }} </strong> </li>
                  <li><strong> Supplier's Ref: </strong> N/A </li>
                  <li><strong> Other Reference(s): </strong> N/A</li>
                  <li><strong> Buyer's Order No: </strong> N/A</li>
                  <!-- <li><strong> Dated: </strong> 17-May-23</li> -->
                  <li><strong> Despatch Document No: </strong> N/A</li>
                  <li><strong> Despatched through: </strong>N/A</li>
                  <li><strong> Vehicle No: </strong>{{$vehicle_number}}</li>

                  </ul>
                </div>
              </div>


            </td>

            </tfoot>
      </table>

      <div class="">
        <div class="content">
          <table class="ui celled table">
            <thead>
              <tr>
              <th>Porduct Code</th>
                <th class="text-center colfix">DESCRIPTION</th>
                <th class="text-center colfix">HSN/SAC</th>
                <th class="text-center colfix">Quantity</th>
                <th class="text-center colfix">Tax</th>
                <th class="text-center colfix">Amount</th>
              </tr>
            </thead>
            <tbody>
              @php
              $itemCount=0;
              @endphp
              @foreach ($carts as $item)
              <tr>
                <td>
                  {{$item->product_code}}
                </td>

                <td class="text-right">


                  @foreach ($item->options as $item1)
                  <span class="mono">{{$item1['item_name']}}</span>
                  <br>
                  @endforeach

             

                </td>
                <td class="text-right">
                  <span class="mono">392310</span>
                  <br>

                </td>
                <td class="text-right">
                  @foreach ($item->options as $item1)

                  @php
                  $itemCount += $item1['item_qty'];
                  @endphp
                  <span class="mono">{{$item1['item_qty']}} NOS</span>
                  <br>
                  @endforeach
              


                </td>
                <td class="text-right">
                  <span class="mono"></span>
                  <br>

                </td>
                <td class="text-right">
                  <strong class="mono">

                  @foreach ($item->options as $item1)
                  <span class="mono">{{$item1['item_price']}}</span>
                  <br>
                  @endforeach

                  </strong>

                </td>
              </tr>
              @endforeach


            </tbody>
            <tfoot class="full-width">
              <tr>
                <th> Total: </th>
                <th colspan="2"></th>
                <th colspan="1"> {{  $itemCount}} NOS </th>
                <th colspan="1"> </th>
                <th colspan="1"> </th>
              </tr>
              <tr>
                <th> Amount Chargeable (in words)</th>
                <th colspan="2"></th>
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <th colspan="1"> E. & O.E </th>
              </tr>
              <tr>
                <th> Recd. in Good Condition </th>
                <th colspan="2"></th>
                <th colspan="1"> for {{$customer['company_name']}} </th>
                <th colspan="1"> Authorised Signatory</th>
                <th colspan="1"> </th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>



      <table class="">

        <tbody>
          <tr>
            <td>
            <div class="ui card">
        <div class="content center aligned text segment">
          <small class="ui sub header"> Amount Details</small>
          <table class="ui celled table">

            <tbody>


              <tr>
                <td colspan="3"><strong>Subtotal</strong></td>
                <td><strong>{{ Cart::subtotal() }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"><strong>Tax</strong></td>
                <td><strong>{{ Cart::tax() }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>{{ Cart::total() }}</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

            </td>
            <td>

              <div class="ui card">
                <div class="content">
                  <div class="header">Notes</div>
                </div>
                <div class="content">
                  Payment is requested within 15 days of recieving this invoice.
                  </br>
                  This is a Computer Generated Document

                </div>
              </div>
            </td>


            </tfoot>
      </table>
    </div>
  </div>


</body>

</html>