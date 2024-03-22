<html lang="en">

<head>
  <!-- External CSS libraries -->
  <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.min.css">
  <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    @media print {

      html,
      body {
        width: 210mm;
        height: 297mm;
      }

      /* ... the rest of the rules ... */
    }

    body {
      background: #EEE;
      /* font-size:0.9em !important; */
    }

    .bigfont {
      font-size: 3rem !important;
    }

    .invoice {
      width: 970px !important;
      margin: 50px auto;
    }

    .logo {
      float: left;
      padding-right: 10px;
      margin: 10px auto;
    }

    dt {
      float: left;
    }

    dd {
      float: left;
      clear: right;
    }

    .customercard {
      min-width: 65%;
    }

    .itemscard {
      min-width: 98.5%;
      margin-left: 0.5%;
    }

    .logo {
      max-width: 5rem;
      margin-top: -0.25rem;
    }

    .invDetails {
      margin-top: 0rem;
    }

    .pageTitle {
      margin-bottom: -1rem;
    }
  </style>

  <script>
    window.console = window.console || function(t) {};
  </script>



</head>

<body translate="no">
  <div class="container invoice">
    <div class="invoice-header">
      <div class="ui left aligned grid">
        <div class="row">
          <h1 class="ui header pageTitle text-center">Order Review <small class="ui sub header"></small></h1>
          <div class="left floated left aligned six wide column">

            <div class="ui">

              <!-- <h4 class="ui sub header invDetails">Delivery Note No: 23-24/09SOGEFIP-108</h4> -->
            </div>
          </div>
          <div class="right floated left aligned six wide column">
            <div class="ui">
              <div class="column two wide right floated">
                <ul class="">
                  <!-- <li><strong>ORIGINAL COPY</strong></li> -->

                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ui segment cards">
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

      <div class="ui segment itemscard">
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


      <!-- BEGIN: Invoice Button -->
      <div class="ui card">
        <div class="content">
          <div class="invoice-btn-section clearfix d-print-none">
            <a class="btn btn-lg btn-primary" href="{{ route('pos.index') }}">
              Back
            </a>
            <button class="btn btn-lg btn-download" type="button" data-bs-toggle="modal" data-bs-target="#modal">
              Pay Now
            </button>
          </div>
        </div>
      </div>
      <!-- END: Invoice Button -->
    </div>

  </div>




  <!-- BEGIN: Modal -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title text-center mx-auto" id="modalCenterTitle">Invoice of {{ $customer->name }}<br />Total Amount ${{ Cart::total() }}</h3>
        </div>

        <form action="{{ route('pos.createOrder') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="modal-body">
              <input type="hidden" name="customer_id" value="{{ $customer->id }}">
              <input type="hidden" name="vehicle_number" value="{{ $vehicle_number }}">
              <div class="mb-3">
                <!-- Form Group (type of product category) -->
                <label class="small mb-1" for="payment_type">Payment <span class="text-danger">*</span></label>
                <select class="form-control @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type">
                  <!-- <option selected="" disabled="">Select a payment:</option> -->
                  <option value="HandCash">HandCash</option>
                  <option value="Cheque">Cheque</option>
                  <option value="Due">Due</option>
                </select>
                @error('payment_type')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="small mb-1" for="pay">Pay Now <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-solid @error('pay') is-invalid @enderror" id="pay" name="pay" placeholder="" value="{{ Cart::total() }}" autocomplete="off" maxlength=""/>
                @error('pay')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-lg btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-lg btn-download" type="submit">Pay</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END: Modal -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</html>