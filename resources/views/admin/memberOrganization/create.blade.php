@extends('admin.layout.frame')
@section('title','Create '.$_panel)

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{"Create ".$_panel}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{$dashboard_url}}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route($_base_route.'.index')}}">{{$_panel}}</a></li>
                            <li class="breadcrumb-item">{{"Create ".$_panel}}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Create<small> {{$_panel}}</small></h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            {!! Form::open ([
                                'url'=> route($_base_route.'.store'),
                                'enctype'=>'multipart/form-data',
                                'id'=>"add-form"
                            ])!!}
                                @include($_view_path.'.common.form',['button'=>'Submit '.$_panel])

                            {!! Form::close() !!}
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6">

                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('js')
    @yield('formJs')
@endsection
