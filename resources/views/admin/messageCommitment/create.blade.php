@extends('admin.layout.frame')
@section('title','Create Message & Commitment')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Message &amp; Commitment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{$dashboard_url}}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.messageCommitment.index')}}">Messages &amp; Commitment</a></li>
                            <li class="breadcrumb-item">Create</li>
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
                                <h3 class="card-title">Create Message &amp; Commitment (सन्देश तथा प्रतिबद्धता)</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            {!! Form::open ([
                                'url'=> route('admin.messageCommitment.store'),
                                'enctype'=>'multipart/form-data',
                                'id'=>"add-form"
                            ])!!}
                                @include('admin.messageCommitment.common.form',['button'=>'Submit Message & Commitment'])

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
