@extends('admin.layout.frame')
@section('title','Show '.$_panel)
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{"Show ".$_panel}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{$dashboard_url}}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route($_base_route.'.index')}}">{{$_panel}}</a></li>
                            <li class="breadcrumb-item">{{"Show ".$_panel}}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">
                                    {{ $data['row']->rank }}
                                    <i class="fas fa-list"></i>
                                    {{ $data['row']->title }} <small class="text-muted">{{  $data['row']->sub_title }} </small>
                                    <a href="{{ $data['row']->menu_url }}" class="btn-link text-secondary">
                                        <i class="fa fa-link"></i>
                                    </a>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12 order-2 order-md-1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="card-title">
                                                    Child List
                                                </h3>
                                                {!!Form::open ([
                                                          'url' => route($_base_route.'.sort'),
                                                          'id' =>'sorting-update-form'
                                                       ]) !!}
                                                <table class="table table-striped">
                                                    <tbody class="ui-sortable">
                                                    @if(isset($data['row']->children) && $data['row']->children->count() > 0)
                                                        @foreach($data['row']->children()->rank()->get() as $key => $child)
                                                            <tr class="ui-sortable-handle">
                                                                <td>
                                                                    <input type="hidden" name="menu_id" value="{{ $data['row']->id }}">
                                                                    <input type="hidden" name="ids[]" value="{{$child->id}}">
                                                                    <i class="fa fa-sort"></i>
                                                                </td>
                                                                <td>{{ $child->title ?? "Menu Title".$key }}</td>
                                                                <td>
                                                                    @can(lcfirst($_panel).'-edit')
                                                                        <a href="{{route($_base_route.'.edit', $child->id)}}" class="btn btn-sm btn-success">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    @endcan
                                                                    @can(lcfirst($_panel).'-delete')
                                                                        <a href="javascript:void(0)" onclick="var c = confirm('{{ "Are you sure?" }}'); if(c){document.getElementById('delete-{{$child->id}}').submit();}" class="btn btn-sm btn-danger">
                                                                           <i class="fa fa-trash"></i>
                                                                        </a>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <th colspan="2"> No Child To Display</th>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $('table tbody').sortable({
                helper: fixWidthHelper,
                update: function (event,ui) {
                    $('#sorting-update-form').submit();
                }
            }).disableSelection();

            function fixWidthHelper (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }
        });
    </script>
@endsection
