@extends('admin.layout.frame')
@section('title','Edit Message & Commitment')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Message &amp; Commitment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{$dashboard_url}}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.messageCommitment.index')}}">Messages &amp; Commitment</a></li>
                            <li class="breadcrumb-item">Edit</li>
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
                                <h3 class="card-title">Edit Message &amp; Commitment <small>({{$data['row']->title}})</small></h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            {!!Form::model($data['row'], [
                                'url' => route('admin.messageCommitment.update', $data['row']->id),
                                'enctype' => 'multipart/form-data',
                                'id'=>"edit-form"
                            ]) !!}
                            @method('PUT')
                            {!! Form::hidden('id', $data['row']->id) !!}
                                @include('admin.messageCommitment.common.form',['button'=>'Update Message & Commitment'])
                            {!! Form::close() !!}
                        </div>
                        <!-- /.card -->
                    </div>
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
