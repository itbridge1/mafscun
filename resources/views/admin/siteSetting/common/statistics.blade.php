<div class="col-md-12">
    <div class="card card-gray-dark">
        <div class="card-header">
            Statistics Detail
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="statistics_form" class="table table-bordered nobottommargin">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Rank</th>

                                <th>
                                    <button id="statistics_detail" type="button" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus">
                                        </i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($data['row']))
                                @include('admin.siteSetting.common.statistics_table-row-edit', [
                                    'row' => $data['row'],
                                ])
                            @else
                                @include('admin.siteSetting.common.statistics_table-row')
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>