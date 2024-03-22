@extends('dashboard.body.main')

@section('specificpagescripts')
<script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endsection

@section('content')
<!-- BEGIN: Header -->
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa fa-shopping-cart"></i></div>
                        Manage Order
                    </h1>
                </div>
            </div>

            <nav class="mt-4 rounded" aria-label="breadcrumb">
                <ol class="breadcrumb px-3 py-2 rounded mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pos.index') }}">POS</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</header>
<!-- END: Header -->

<!-- BEGIN: Main Page Content -->
<div class="container-xl px-2 mt-n10">
 
        <div class="row">
         

            <div class="col-xl-12">
             
                
        <!-- BEGIN: Section Left -->
        <div class="col-xl-12">
            <!-- BEGIN: Cart -->
            <div class="card mb-4">
                <div class="card-header">
                    Cart
                </div>
                <div class="card-body">
                    <!-- BEGIN: Table Cart -->

                    <h5 class="card-title"><b>Product Code : </b>{{$product->product_code}}</h5>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                              
                                    <th scope="col">Name</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Buying Price</th>
                                    <th scope="col">Selling Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $price = 0;
                                $i=1;
                                @endphp
                                
                                @foreach ($product->product as $item)
                                @php
                                $array['item_details'][$item->id]=['item_price'=>$item->selling_price,'item_name'=>$item->product_name,'item_id'=>$item->id,'item_qty'=>'1'];
                                Session::put('item_details',   $array);

                                @endphp
                                <tr>
                             
                                    <td scope="row">{{ $item->product_name }}</td>
                                    <td style="min-width: 170px;">
                                       <div class="input-group" style="width:25%">
                                            <button class=" minus"  type="button">-</button>
<input type="number" class="form-control quantity" readonly min="1" name="qty"  required value="1" style="width: 50%; padding: 5px;    outline: none;
    text-align: center;border: 1px solid #ccc; border-radius: 5px;">
<input type="hidden" name="buying_price" class="buying_price" value="{{$item->buying_price}}">
                        <input type="hidden" name="stock" class="stock" value="{{$item->stock}}">
                        <input type="hidden" name="item_id" class="item_id" value="{{$item->id}}">
                        <button  type="button" class="add">+</button>
                        
                                       
                                            </div>
                                  
                                    </td>
                                    <td class="text-center">{{ $item->buying_price }}</td>
                                    <td class="text-center">{{ $item->selling_price }}</td>
                                   
                                </tr>

                                @php
                               
                                $price += $item->selling_price;
                                $i++;
                                @endphp
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- END: Table Cart -->

                    <!-- Form Row -->
                    <div class="row gx-3 mb-3">
                        <!-- Form Group (total product) -->
                        <div class="col-md-6">
                            <label class="small mb-1">Total Items</label>
                            <div class="form-control form-control-solid fw-bold text-red">{{ count($product->product) }}</div>
                        </div>
                        <!-- Form Group (subtotal) -->
                        <div class="col-md-6">
                            <label class="small mb-1">Subtotal</label>
                            <div class="form-control form-control-solid fw-bold text-red " id="total_price">{{ $price }}</div>
                        </div>
                    </div>
                    <!-- Form Row -->
                    <div class="row gx-3 mb-3">
                        <!-- Form Group (tax) -->
                        <!-- <div class="col-md-6">
                            <label class="small mb-1">Tax</label>
                            <div class="form-control form-control-solid fw-bold text-red">{{ Cart::tax() }}</div>
                        </div> -->
                        <!-- Form Group (total) -->
                        <!-- <div class="col-md-6">
                            <label class="small mb-1">Total</label>
                            <div class="form-control form-control-solid fw-bold text-red">{{ Cart::total() }}</div>
                        </div> -->
                    </div>
                    <!-- Form Group (customer) -->
                    <div class="" style="    display: inline-flex;">
                    <span class="pull-left">
                    @if(count($product->product)>0)
                    <form action="{{ route('pos.addCartItem', $product->id) }}" method="POST" class="">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $product->id }}-{{rand(10,100)}}">
                                                    <input type="hidden" name="name" value="{{ $product->product_code }}">
                                                    <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">
                                                    <input type="hidden" name="price" value="{{ $price  }}" id="price">
                                                    

                                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa-solid fa-plus"></i>Add To Cart
                                                    </button>
                     </form>
                     @endif
                    </span>
                    <span class="pull-right" style="margin-left: 14px;"> <a class="btn btn-outline-danger btn-sm" href="{{ route('pos.index') }}">Cancel</a></span>
                    </div>
                </div>
            </div>
            <!-- END: Cart -->
        </div>
        <!-- END: Section Left -->
                <!-- END: Supplier Details -->
            </div>
        </div>

</div>
<!-- END: Main Page Content -->
<script>


    </script>
@endsection
