@extends('admin.layout.frame')
@section('title','Edit '.$_panel)
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{"Edit ".$_panel}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{$dashboard_url}}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route($_base_route.'.index')}}">{{$_panel}}</a></li>
                            <li class="breadcrumb-item">{{"Edit ".$_panel}}</li>
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
                                <h3 class="card-title">{{$_panel}} <small>Edit {{$_panel}}</small></h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            {!!Form::model($data['row'], [
                                'url' => route($_base_route.'.update', $data['row']->id),
                                'enctype' => 'multipart/form-data',
                                'id'=>"edit-form"
                            ]) !!}
                            @method('PUT')
                            {!! Form::hidden('id', $data['row']->id) !!}
                                @include($_view_path.'.common.form',['button'=>'Update '.$_panel])
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
