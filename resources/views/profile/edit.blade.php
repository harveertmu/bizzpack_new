@extends('dashboard.body.main')

@section('specificpagescripts')
<script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endsection

@section('content')
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="user"></i></div>
                        Account Settings - Profile
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- BEGIN: Main page content -->
<div class="container-xl px-4 mt-4">
    <!-- Account page navigation -->
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="{{ route('profile.edit') }}">Profile</a>
        <a class="nav-link" href="{{ route('profile.settings') }}">Settings</a>
    </nav>

    <hr class="mt-0 mb-4" />

    <!-- BEGIN: Alert -->
    @if (session()->has('success'))
    <div class="alert alert-success alert-icon" role="alert">
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-icon-aside">
            <i class="far fa-flag"></i>
        </div>
        <div class="alert-icon-content">
            {{ session('success') }}
        </div>
    </div>
    @endif
    <!-- END: Alert -->

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card -->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image -->
                        <img class="img-account-profile rounded-circle mb-2" src="{{ $user->photo ? asset('profile_image/'.$user->photo) : asset('assets/img/demo/user-placeholder.svg') }}" alt="" id="image-preview" />
                        <!-- Profile picture help block -->
                        <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 1 MB</div>
                        <!-- Profile picture input -->
                        <input class="form-control form-control-solid mb-2 @error('photo') is-invalid @enderror" type="file"  id="image" name="photo" accept="image/*" onchange="previewImage();">
                        @error('photo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <!-- Account details card -->
                <div class="card mb-4">
                    <div class="card-header">
                        Account Details
                    </div>
                    <div class="card-body">
                        <!-- Form Group (username) -->
                        
                        <div class="mb-3">
                            <label class="small mb-1" for="name">Company Name <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid @error('company_name') is-invalid @enderror" id="company_name" name="company_name" type="text" placeholder="" value="{{ old('company_name', $user->company_name) }}" />
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="gst_number">GST Number <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid @error('gst_number') is-invalid @enderror" id="gst_number" name="gst_number" type="text" placeholder="" value="{{ old('gst_number', $user->gst_number) }}" />
                            @error('gst_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="username">Username</label>
                            <input class="form-control form-control-solid @error('username') is-invalid @enderror" id="username" name="username" type="text" placeholder="" value="{{ old('username', $user->username) }}" autocomplete="off" />
                            @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        </div>

                        <!-- Form Group (name) -->
                        <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="name">Full name</label>
                            <input class="form-control form-control-solid @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="" value="{{ old('name', $user->name) }}" autocomplete="off" />
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- Form Group (email address) -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="email">Email address</label>
                            <input class="form-control form-control-solid @error('photo') is-invalid @enderror" id="email" name="email" type="text" placeholder="" value="{{ old('email', $user->email) }}"  autocomplete="off" />
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <!-- Form Group (account holder) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="zip">Zip<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('zip') is-invalid @enderror" id="zip" name="zip" type="text" placeholder="" value="{{ old('zip',$user->zip) }}" />
                                @error('zip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <!-- Form Group (account_name) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="city">City<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('city') is-invalid @enderror" id="city" name="city" type="text" placeholder="" value="{{ old('city',$user->city) }}" />
                                @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- Form Group (address) -->


                         <!-- Form Row -->
                         <div class="row gx-3 mb-3">
                            <!-- Form Group (account holder) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="state">State<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('state') is-invalid @enderror" id="state" name="state" type="text" placeholder="" value="{{ old('state',$user->state) }}" />
                                @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <!-- Form Group (account_name) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="street_address*">Street Address<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('street_address*') is-invalid @enderror" id="street_address*" name="street_address" type="text" placeholder="" value="{{ old('street_address*',$user->street_address) }}" />
                                @error('street_address*')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- Form Group (address) -->


                        


                        <div class="mb-3">
                                <label for="address_apt">Apartment, suite, unit, etc. </label>
                                <textarea class="form-control form-control-solid @error('address_apt') is-invalid @enderror" id="address_apt" name="address_apt" rows="3">{{ old('address_apt',$user->address_apt) }}</textarea>
                                @error('address_apt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>

                        <!-- Save changes button -->
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END: Main page content -->
@endsection
