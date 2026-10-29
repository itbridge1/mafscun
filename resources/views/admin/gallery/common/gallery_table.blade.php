<div class="col-md-12">
    <div class="card card-gray-dark">
        <div class="card-header">
            Gallery Images
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="gallery_form" class="table table-bordered nobottommargin">
                        <thead>
                        <tr>
                            <th>Choose Image</th>
                            <th>Alt Text</th>
                            <th>Caption</th>
                            <th>Status</th>
                            <th>
                                <button id="gallery_detail" type="button"  class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="ui-sortable">
                        @if(isset($data['row']))
                            @include('admin.gallery.common.gallery-row-edit',['row' => $data['row']])
                        @else
                            @include('admin.gallery.common.gallery-row')
                        @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
