@extends('admin.layout.head')


@section('content')
<div class="right_col" role="main" style="min-height: 3793px;">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Role Managment</h3>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

            <div class="title_right">
                <div class="col-md-5 col-sm-5  form-group pull-right top_search">

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Create New Role</h2>
                        <ul class="nav navbar-right panel_toolbox">

                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                <!-- <input type="text" id="first-name" required="required" class="form-control "> -->
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Permission <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">

                                @foreach($permission as $value)
                                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                    {{ $value->name }}</label>
                                <br />
                                @endforeach
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Cancel</a>
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>



    @endsection