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
                        <div class="page-header-icon"><i class="fa fa-barcode" aria-hidden="true"></i></div>
Edit                    </h1>
                </div>
            </div>

            <nav class="mt-4 rounded" aria-label="breadcrumb">
                <ol class="breadcrumb px-3 py-2 rounded mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product-code.index') }}">Product Code</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</header>
<!-- END: Header -->

<!-- BEGIN: Main Page Content -->
<div class="container-xl px-2 mt-n10">
<form action="{{ route('product-code.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
          

            <div class="col-xl-12">
                <!-- BEGIN: Product Code Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        Product Code Details
                    </div>
                    <div class="card-body">

					<div class="row gx-3 mb-3">

					<div class="col-md-6">
                                <label class="small mb-1" for="vendor">Vendor</label>
                                <select class="form-select form-control-solid @error('vendor') is-invalid @enderror" id="vendor" name="vendor">
                                    <option selected="" disabled="">Select a vendor:</option>
                                    @foreach($vendors as $vendor)
                                        <option @if(old('vendor', $product->vendor_id) == $vendor->id) selected="selected" @endif value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                                    @endforeach
                                                        
                                </select>
                                @error('vendor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
							<div class="col-md-6">
                            <label class="small mb-1" for="product_code">Product Code<span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid @error('product_code') is-invalid @enderror" id="product_code" name="product_code" type="text" placeholder="" value="{{ old('product_code', $product->product_code) }}" />
                            @error('product_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

						
				</div>
                       
                        
                        <!-- Form Group (name) -->

                        <div class="mb-3">
                                <label for="description">Description. </label>
                                <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description',$product->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>

                        <!-- Submit button -->
						<button class="btn btn-primary" type="submit">Update</button>
                        <a class="btn btn-danger" href="{{ route('product-code.index') }}">Cancel</a>
                    </div>
                </div>
                <!-- END: Supplier Details -->
            </div>
        </div>
    </form>
</div>
<!-- END: Main Page Content -->
@endsection
