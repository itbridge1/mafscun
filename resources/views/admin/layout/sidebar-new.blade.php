<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{ \App\Facades\ViewHelper::getImagePath('siteProfile',$_site_profile->logo) }}" alt="HMS" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $_site_profile->name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ \App\Facades\ViewHelper::getImagePath('user',auth()->user()->photo) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

    {{--        <!-- SidebarSearch Form -->--}}
    {{--        <div class="form-inline">--}}
    {{--            <div class="input-group" data-widget="sidebar-search">--}}
    {{--                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">--}}
    {{--                <div class="input-group-append">--}}
    {{--                    <button class="btn btn-sidebar">--}}
    {{--                        <i class="fas fa-search fa-fw"></i>--}}
    {{--                    </button>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{request()->is('admin/dashboard')?'active':''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">ADMIN SECTION</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            ACL
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" >
                        @can(['role-create','role-index'])
                            <li class="nav-item {{request()->is('admin/role*')?'menu-open':''}}">
                                <a href="{{route('admin.role.index')}}" class="nav-link {{request()->is('admin/role*')?'active':''}}">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Role
                                        {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                            <ul class="nav nav-treeview">--}}
                                {{--                                @can('role-create')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('admin.role.create')}}" class="nav-link {{request()->is('admin/role/create')?'active':''}}">--}}
                                {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                            <p>Add Role</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                {{--                                @can('role-index')--}}
                                {{--                                    <li class="nav-item">--}}
                                {{--                                        <a href="{{route('admin.role.index')}}" class="nav-link {{request()->is('admin/role')?'active':''}}">--}}
                                {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                            <p>List Role</p>--}}
                                {{--                                        </a>--}}
                                {{--                                    </li>--}}
                                {{--                                @endcan--}}
                                {{--                            </ul>--}}
                            </li>
                        @endcan

                        @role('super-dev')
                        <li class="nav-item {{request()->is('admin/permission*')?'menu-open':''}}">
                            <a href="{{route('admin.permission.index')}}" class="nav-link {{request()->is('admin/permission*')?'active':''}}">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    Permission
                                    {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                </p>
                            </a>
                            {{--                            <ul class="nav nav-treeview">--}}
                            {{--                                @can('permission-create')--}}
                            {{--                                    <li class="nav-item">--}}
                            {{--                                        <a href="{{route('admin.permission.create')}}" class="nav-link {{request()->is('admin/permission/create')?'active':''}}">--}}
                            {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                            <p>Add Permission</p>--}}
                            {{--                                        </a>--}}
                            {{--                                    </li>--}}
                            {{--                                @endcan--}}
                            {{--                                @can('permission-index')--}}
                            {{--                                    <li class="nav-item">--}}
                            {{--                                        <a href="{{route('admin.permission.index')}}" class="nav-link {{request()->is('admin/permission')?'active':''}}">--}}
                            {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                            <p>List Permission</p>--}}
                            {{--                                        </a>--}}
                            {{--                                    </li>--}}
                            {{--                                @endcan--}}
                            {{--                            </ul>--}}
                        </li>
                        @endrole

                        <li class="nav-item {{request()->is('admin/user*')?'menu-open':''}}">
                            <a href="{{route('admin.user.index')}}" class="nav-link {{request()->is('admin/user*')?'active':''}}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User
                                    {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                </p>
                            </a>
                            {{--                            <ul class="nav nav-treeview">--}}
                            {{--                                @can('user-create')--}}
                            {{--                                    <li class="nav-item">--}}
                            {{--                                        <a href="{{route('admin.user.create')}}" class="nav-link {{request()->is('admin/user/create')?'active':''}}">--}}
                            {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                            <p>Add User</p>--}}
                            {{--                                        </a>--}}
                            {{--                                    </li>--}}
                            {{--                                @endcan--}}
                            {{--                                @can('user-index')--}}
                            {{--                                    <li class="nav-item">--}}
                            {{--                                        <a href="{{route('admin.user.index')}}" class="nav-link {{request()->is('admin/user')?'active':''}}">--}}
                            {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                            <p>List User</p>--}}
                            {{--                                        </a>--}}
                            {{--                                    </li>--}}
                            {{--                                @endcan--}}
                            {{--                            </ul>--}}
                        </li>

                        @can('siteProfile-index')
                            <li class="nav-item">
                                <a href="{{ route('admin.siteProfile.index') }}"
                                   class="nav-link">
                                    <i class="nav-icon fas fa-project-diagram"></i>
                                    <p>
                                        Site Profile
                                    </p>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endcan

                        @can(['fiscalYear-create','fiscalYear-index'])
                            <li class="nav-item {{request()->is('admin/fiscalYear*')?'menu-open':''}}">
                                <a href="{{route('admin.fiscalYear.index')}}" class="nav-link {{request()->is('admin/fiscalYear*')?'active':''}}">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>
                                        FiscalYear
                                        {{--                                            <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                    <ul class="nav nav-treeview">--}}
                                {{--                                        @can('fiscalYear-create')--}}
                                {{--                                            <li class="nav-item">--}}
                                {{--                                                <a href="{{route('admin.fiscalYear.create')}}" class="nav-link {{request()->is('admin/fiscalYear/create')?'active':''}}">--}}
                                {{--                                                    <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                    <p>Add FiscalYear</p>--}}
                                {{--                                                </a>--}}
                                {{--                                            </li>--}}
                                {{--                                        @endcan--}}
                                {{--                                        @can('fiscalYear-index')--}}
                                {{--                                            <li class="nav-item">--}}
                                {{--                                                <a href="{{route('admin.fiscalYear.index')}}" class="nav-link {{request()->is('admin/fiscalYear')?'active':''}}">--}}
                                {{--                                                    <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                    <p>List FiscalYear</p>--}}
                                {{--                                                </a>--}}
                                {{--                                            </li>--}}
                                {{--                                        @endcan--}}
                                {{--                                    </ul>--}}
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            General Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" >
                        @can(['country-create','country-index'])
                            <li class="nav-item {{request()->is('admin/country*')?'menu-open':''}}">
                                <a href="{{route('admin.country.index')}}" class="nav-link {{request()->is('admin/country*')?'active':''}}">
                                    <i class="nav-icon fas fa-globe"></i>
                                    <p>
                                        Country
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('country-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.country.create')}}" class="nav-link {{request()->is('admin/country/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Country</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('country-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.country.index')}}" class="nav-link {{request()->is('admin/country')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Country</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['currency-create','currency-index'])
                            <li class="nav-item {{request()->is('admin/currency*')?'menu-open':''}}">
                                <a href="{{route('admin.currency.index')}}" class="nav-link {{request()->is('admin/currency*')?'active':''}}">
                                    <i class="nav-icon fas fa-coins"></i>
                                    <p>
                                        Currency
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('currency-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.currency.create')}}" class="nav-link {{request()->is('admin/currency/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Currency</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('currency-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.currency.index')}}" class="nav-link {{request()->is('admin/currency')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Currency</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['department-create','department-index'])
                            <li class="nav-item {{request()->is('admin/department*')?'menu-open':''}}">
                                <a href="{{route('admin.department.index')}}" class="nav-link {{request()->is('admin/department*')?'active':''}}">
                                    <i class="nav-icon fas fa-hospital-alt"></i>
                                    <p>
                                        Department
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('department-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.department.create')}}" class="nav-link {{request()->is('admin/department/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Department</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('department-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.department.index')}}" class="nav-link {{request()->is('admin/department')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Department</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan
                        @can(['floor-create','floor-index'])
                            <li class="nav-item {{request()->is('admin/floor*')?'menu-open':''}}">
                                <a href="{{route('admin.floor.index')}}" class="nav-link {{request()->is('admin/floor*')?'active':''}}">
                                    <i class="nav-icon fas fa-hospital"></i>
                                    <p>
                                        Floor
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('floor-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.floor.create')}}" class="nav-link {{request()->is('admin/floor/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Floor</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('floor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.floor.index')}}" class="nav-link {{request()->is('admin/floor')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Floor</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['room-create','room-index'])
                            <li class="nav-item {{request()->is('admin/room*')?'menu-open':''}}">
                                <a href="{{route('admin.room.index')}}" class="nav-link {{request()->is('admin/room*')?'active':''}}">
                                    <i class="nav-icon fas fa-door-open"></i>
                                    <p>
                                        Room
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('room-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.room.create')}}" class="nav-link {{request()->is('admin/room/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Room</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('room-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.room.index')}}" class="nav-link {{request()->is('admin/room')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Room</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['designation-create','designation-index'])
                            <li class="nav-item {{request()->is('admin/designation*')?'menu-open':''}}">
                                <a href="{{route('admin.designation.index')}}" class="nav-link {{request()->is('admin/designation*')?'active':''}}">
                                    <i class="nav-icon fas fa-check"></i>
                                    <p>
                                        Designation
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('designation-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.designation.create')}}" class="nav-link {{request()->is('admin/designation/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Designation</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('designation-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.designation.index')}}" class="nav-link {{request()->is('admin/designation')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Designation</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['services-create','services-index'])
                            <li class="nav-item {{request()->is('admin/services*')?'menu-open':''}}">
                                <a href="{{route('admin.services.index')}}" class="nav-link {{request()->is('admin/services*')?'active':''}}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Services
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('services-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.services.create')}}" class="nav-link {{request()->is('admin/services/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Services</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('services-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.services.index')}}" class="nav-link {{request()->is('admin/services')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Services</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['block-create','block-index'])
                            <li class="nav-item {{request()->is('admin/block*')?'menu-open':''}}">
                                <a href="{{route('admin.block.index')}}" class="nav-link {{request()->is('admin/block*')?'active':''}}">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Block
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('block-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.block.create')}}" class="nav-link {{request()->is('admin/block/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Block</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('block-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.block.index')}}" class="nav-link {{request()->is('admin/block')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Block</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['bloodGroup-create','bloodGroup-index'])
                            <li class="nav-item {{request()->is('admin/bloodGroup*')?'menu-open':''}}">
                                <a href="{{route('admin.bloodGroup.index')}}" class="nav-link {{request()->is('admin/bloodGroup*')?'active':''}}">
                                    <i class="nav-icon fas fa-tint"></i>
                                    <p>
                                        BloodGroup
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('bloodGroup-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.bloodGroup.create')}}" class="nav-link {{request()->is('admin/bloodGroup/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add BloodGroup</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('bloodGroup-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.bloodGroup.index')}}" class="nav-link {{request()->is('admin/bloodGroup')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List BloodGroup</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['company-create','company-index'])
                            <li class="nav-item {{request()->is('admin/company*')?'menu-open':''}}">
                                <a href="{{route('admin.company.index')}}" class="nav-link {{request()->is('admin/company*')?'active':''}}">
                                    <i class="nav-icon fas fa-city"></i>
                                    <p>
                                        Company
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('company-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.company.create')}}" class="nav-link {{request()->is('admin/company/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Company</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('company-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.company.index')}}" class="nav-link {{request()->is('admin/company')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Company</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['doctor-create','doctor-index'])
                            <li class="nav-item {{request()->is('admin/doctor*')?'menu-open':''}}">
                                <a href="{{route('admin.doctor.index')}}" class="nav-link {{request()->is('admin/doctor*')?'active':''}}">
                                    <i class="nav-icon fas fa-user-md"></i>
                                    <p>
                                        Doctor
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('doctor-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.doctor.create')}}" class="nav-link {{request()->is('admin/doctor/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Doctor</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('doctor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.doctor.index')}}" class="nav-link {{request()->is('admin/doctor')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Doctor</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['occupation-create','occupation-index'])
                            <li class="nav-item {{request()->is('admin/occupation*')?'menu-open':''}}">
                                <a href="{{route('admin.occupation.index')}}" class="nav-link {{request()->is('admin/occupation*')?'active':''}}">
                                    <i class="nav-icon fas fa-briefcase"></i>
                                    <p>
                                        Occupation
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('occupation-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.occupation.create')}}" class="nav-link {{request()->is('admin/occupation/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Occupation</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('doctor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.occupation.index')}}" class="nav-link {{request()->is('admin/occupation')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Occupation</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['qualification-create','qualification-index'])
                            <li class="nav-item {{request()->is('admin/qualification*')?'menu-open':''}}">
                                <a href="{{route('admin.qualification.index')}}" class="nav-link {{request()->is('admin/qualification*')?'active':''}}">
                                    <i class="nav-icon fas fa-graduation-cap"></i>
                                    <p>
                                        Qualification
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('qualification-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.qualification.create')}}" class="nav-link {{request()->is('admin/qualification/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Qualification</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('doctor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.qualification.index')}}" class="nav-link {{request()->is('admin/qualification')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Qualification</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['religion-create','religion-index'])
                            <li class="nav-item {{request()->is('admin/religion*')?'menu-open':''}}">
                                <a href="{{route('admin.religion.index')}}" class="nav-link {{request()->is('admin/religion*')?'active':''}}">
                                    <i class="nav-icon fas fa-praying-hands"></i>
                                    <p>
                                        Religion
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('religion-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.religion.create')}}" class="nav-link {{request()->is('admin/religion/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Religion</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('doctor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.religion.index')}}" class="nav-link {{request()->is('admin/religion')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Religion</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['ward-create','ward-index'])
                            <li class="nav-item {{request()->is('admin/ward*')?'menu-open':''}}">
                                <a href="{{route('admin.ward.index')}}" class="nav-link {{request()->is('admin/ward*')?'active':''}}">
                                    <i class="nav-icon fas fa-bed"></i>
                                    <p>
                                        Ward
                                        {{--                                        <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                <ul class="nav nav-treeview">--}}
                                {{--                                    @can('ward-create')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.ward.create')}}" class="nav-link {{request()->is('admin/ward/create')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>Add Ward</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                    @can('doctor-index')--}}
                                {{--                                        <li class="nav-item">--}}
                                {{--                                            <a href="{{route('admin.ward.index')}}" class="nav-link {{request()->is('admin/ward')?'active':''}}">--}}
                                {{--                                                <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                <p>List Ward</p>--}}
                                {{--                                            </a>--}}
                                {{--                                        </li>--}}
                                {{--                                    @endcan--}}
                                {{--                                </ul>--}}
                            </li>
                        @endcan

                        @can(['scheme-create','scheme-index'])
                            <li class="nav-item {{request()->is('admin/scheme*')?'menu-open':''}}">
                                <a href="{{route('admin.scheme.index')}}" class="nav-link {{request()->is('admin/scheme*')?'active':''}}">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>
                                        Scheme
                                        {{--                                            <i class="right fas fa-angle-left"></i>--}}
                                    </p>
                                </a>
                                {{--                                    <ul class="nav nav-treeview">--}}
                                {{--                                        @can('scheme-create')--}}
                                {{--                                            <li class="nav-item">--}}
                                {{--                                                <a href="{{route('admin.scheme.create')}}" class="nav-link {{request()->is('admin/scheme/create')?'active':''}}">--}}
                                {{--                                                    <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                    <p>Add Scheme</p>--}}
                                {{--                                                </a>--}}
                                {{--                                            </li>--}}
                                {{--                                        @endcan--}}
                                {{--                                        @can('doctor-index')--}}
                                {{--                                            <li class="nav-item">--}}
                                {{--                                                <a href="{{route('admin.scheme.index')}}" class="nav-link {{request()->is('admin/scheme')?'active':''}}">--}}
                                {{--                                                    <i class="far fa-circle nav-icon"></i>--}}
                                {{--                                                    <p>List Scheme</p>--}}
                                {{--                                                </a>--}}
                                {{--                                            </li>--}}
                                {{--                                        @endcan--}}
                                {{--                                    </ul>--}}
                            </li>
                        @endcan
                    </ul>
                </li>

                @can(['bill-create','bill-index'])
                    <li class="nav-header">BILLING SECTION</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                Billing Setting
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" >
                            @can(['examination-create','examination-index'])
                                <li class="nav-item {{request()->is('billing/examination*')?'menu-open':''}}">
                                    <a href="{{route('billing.examination.index')}}" class="nav-link {{request()->is('billing/examination*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription-bottle"></i>
                                        <p>
                                            Exam
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('examination-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('billing.examination.create')}}" class="nav-link {{request()->is('billing/examination/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Exam</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('examination-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('billing.examination.index')}}" class="nav-link {{request()->is('billing/examination')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Exam</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan

                            @can(['test-create','test-index'])
                                <li class="nav-item {{request()->is('billing/test*')?'menu-open':''}}">
                                    <a href="{{route('billing.test.index')}}" class="nav-link {{request()->is('billing/test*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription"></i>
                                        <p>
                                            Test
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('test-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('billing.test.create')}}" class="nav-link {{request()->is('billing/test/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Test</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('test-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('billing.test.index')}}" class="nav-link {{request()->is('billing/test')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Test</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li class="nav-item {{request()->is('billing/bill*')?'menu-open':''}}">
                        <a href="{{route('billing.bill.index')}}" class="nav-link {{request()->is('billing/bill*')?'active':''}}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Bill
                                {{--                            <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                    <ul class="nav nav-treeview">--}}
                        {{--                        @can('bill-create')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('billing.bill.create')}}" class="nav-link {{request()->is('billing/bill/create')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>Add Bill</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                        @can('bill-index')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('billing.bill.index')}}" class="nav-link {{request()->is('billing/bill')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>List Bill</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                    </ul>--}}
                    </li>
                @endcan
                @can(['patientRegistration-create','patientRegistration-index'])
                    <li class="nav-header">REGISTRATION SECTION</li>
                @endcan
                @can(['patientType-create','patientType-index'])
                    <li class="nav-item {{request()->is('registration/patientType*')?'menu-open':''}}">
                        <a href="{{route('registration.patientType.index')}}" class="nav-link {{request()->is('registration/patientType*')?'active':''}}">
                            <i class="nav-icon fas fa-user-nurse"></i>
                            <p>
                                PatientType
                                {{--                            <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                    <ul class="nav nav-treeview">--}}
                        {{--                        @can('patientType-create')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.patientType.create')}}" class="nav-link {{request()->is('registration/patientType/create')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>Add PatientType</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                        @can('patientType-index')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.patientType.index')}}" class="nav-link {{request()->is('registration/patientType')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>List PatientType</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                    </ul>--}}
                    </li>
                @endcan

                @can(['registrationSetting-create','registrationSetting-index'])
                    <li class="nav-item {{request()->is('registration/registrationSetting*')?'menu-open':''}}">
                        <a href="{{route('registration.registrationSetting.index')}}" class="nav-link {{request()->is('registration/registrationSetting*')?'active':''}}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                RegistrationSetting
                                {{--                            <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                    <ul class="nav nav-treeview">--}}
                        {{--                        @can('registrationSetting-create')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.registrationSetting.create')}}" class="nav-link {{request()->is('registration/registrationSetting/create')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>Add RegistrationSetting</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                        @can('registrationSetting-index')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.registrationSetting.index')}}" class="nav-link {{request()->is('registration/registrationSetting')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>List RegistrationSetting</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                    </ul>--}}
                    </li>
                @endcan

                @can(['patientRegistration-create','patientRegistration-index'])
                    <li class="nav-item {{request()->is('registration/patientRegistration*')?'menu-open':''}}">
                        <a href="{{route('registration.patientRegistration.index')}}" class="nav-link {{request()->is('registration/patientRegistration*')?'active':''}}">
                            <i class="nav-icon fas fa-registered"></i>
                            <p>
                                PatientRegistration
                                {{--                            <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                    <ul class="nav nav-treeview">--}}
                        {{--                        @can('patientRegistration-create')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.patientRegistration.create')}}" class="nav-link {{request()->is('registration/patientRegistration/create')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>Add PatientRegistration</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                        @can('patientRegistration-index')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.patientRegistration.index')}}" class="nav-link {{request()->is('registration/patientRegistration')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>List PatientRegistration</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                    </ul>--}}
                    </li>
                @endcan

                @can(['ipdRegistration-create','ipdRegistration-index'])
                    <li class="nav-item {{request()->is('registration/ipdRegistration*')?'menu-open':''}}">
                        <a href="{{route('registration.ipdRegistration.index')}}" class="nav-link {{request()->is('registration/ipdRegistration*')?'active':''}}">
                            <i class="nav-icon fas fa-i-cursor"></i>
                            <p>
                                IPD Registration
                                {{--                            <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                    <ul class="nav nav-treeview">--}}
                        {{--                        @can('ipdRegistration-create')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.ipdRegistration.create')}}" class="nav-link {{request()->is('registration/ipdRegistration/create')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>Add IPD Registration</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                        @can('ipdRegistration-index')--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{route('registration.ipdRegistration.index')}}" class="nav-link {{request()->is('registration/ipdRegistration')?'active':''}}">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                    <p>List IPD Registration</p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endcan--}}
                        {{--                    </ul>--}}
                    </li>
                @endcan

                @can(['wardAdmission-create','wardAdmission-index'])
                    <li class="nav-item {{request()->is('registration/wardAdmission*')?'menu-open':''}}">
                        <a href="{{route('registration.wardAdmission.index')}}" class="nav-link {{request()->is('registration/wardAdmission*')?'active':''}}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Ward Admission
                                {{--                                <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                        <ul class="nav nav-treeview">--}}
                        {{--                            @can('wardAdmission-create')--}}
                        {{--                                <li class="nav-item">--}}
                        {{--                                    <a href="{{route('registration.wardAdmission.create')}}" class="nav-link {{request()->is('registration/wardAdmission/create')?'active':''}}">--}}
                        {{--                                        <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                        <p>Add  Ward Admission</p>--}}
                        {{--                                    </a>--}}
                        {{--                                </li>--}}
                        {{--                            @endcan--}}
                        {{--                            @can('wardAdmission-index')--}}
                        {{--                                <li class="nav-item">--}}
                        {{--                                    <a href="{{route('registration.wardAdmission.index')}}" class="nav-link {{request()->is('registration/wardAdmission')?'active':''}}">--}}
                        {{--                                        <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                        <p>List  Ward Admission</p>--}}
                        {{--                                    </a>--}}
                        {{--                                </li>--}}
                        {{--                            @endcan--}}
                        {{--                        </ul>--}}
                    </li>
                @endcan

                @can(['wardDischarge-create','wardDischarge-index'])
                    <li class="nav-item {{request()->is('registration/wardDischarge*')?'menu-open':''}}">
                        <a href="{{route('registration.wardDischarge.index')}}" class="nav-link {{request()->is('registration/wardDischarge*')?'active':''}}">
                            <i class="nav-icon fas fa-outdent"></i>
                            <p>
                                Ward Discharge
                                {{--                                <i class="right fas fa-angle-left"></i>--}}
                            </p>
                        </a>
                        {{--                        <ul class="nav nav-treeview">--}}
                        {{--                            @can('wardDischarge-create')--}}
                        {{--                                <li class="nav-item">--}}
                        {{--                                    <a href="{{route('registration.wardDischarge.create')}}" class="nav-link {{request()->is('registration/wardDischarge/create')?'active':''}}">--}}
                        {{--                                        <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                        <p>Add  Ward Discharge</p>--}}
                        {{--                                    </a>--}}
                        {{--                                </li>--}}
                        {{--                            @endcan--}}
                        {{--                            @can('wardDischarge-index')--}}
                        {{--                                <li class="nav-item">--}}
                        {{--                                    <a href="{{route('registration.wardDischarge.index')}}" class="nav-link {{request()->is('registration/wardDischarge')?'active':''}}">--}}
                        {{--                                        <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                        <p>List  Ward Discharge</p>--}}
                        {{--                                    </a>--}}
                        {{--                                </li>--}}
                        {{--                            @endcan--}}
                        {{--                        </ul>--}}
                    </li>
                @endcan
                @can([
                    'labDepartment-create',
                    'labDepartment-index',
                    'labTestType-create',
                    'labTestType-index',
                    'labTest-create',
                    'labTest-index',
                    'labTestDetail-create',
                    'labTestDetail-index'
                ])
                    <li class="nav-header">LAB SECTION</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                LAB Setting
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" >
                            @can(['labDepartment-create','labDepartment-index'])
                                <li class="nav-item {{request()->is('lab/labDepartment*')?'menu-open':''}}">
                                    <a href="{{route('lab.labDepartment.index')}}" class="nav-link {{request()->is('lab/labDepartment*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription-bottle"></i>
                                        <p>
                                            Lab Department
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('labDepartment-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labDepartment.create')}}" class="nav-link {{request()->is('lab/labDepartment/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Lab Department</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('labDepartment-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labDepartment.index')}}" class="nav-link {{request()->is('lab/labDepartment')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Lab Department</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan

                            @can(['labTestType-create','labTestType-index'])
                                <li class="nav-item {{request()->is('lab/labTestType*')?'menu-open':''}}">
                                    <a href="{{route('lab.labTestType.index')}}" class="nav-link {{request()->is('lab/labTestType*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription"></i>
                                        <p>
                                            Lab Test Type
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('labTestType-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTestType.create')}}" class="nav-link {{request()->is('lab/labTestType/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Lab Test Type</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('labTestType-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTestType.index')}}" class="nav-link {{request()->is('lab/labTestType')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Lab Test Type</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan

                            @can(['labTest-create','labTest-index'])
                                <li class="nav-item {{request()->is('lab/labTest*')?'menu-open':''}}">
                                    <a href="{{route('lab.labTest.index')}}" class="nav-link {{request()->is('lab/labTest*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription"></i>
                                        <p>
                                            Lab Test
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('labTest-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTest.create')}}" class="nav-link {{request()->is('lab/labTest/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Lab Test</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('labTest-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTest.index')}}" class="nav-link {{request()->is('lab/labTest')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Lab Test</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan

                            @can(['labTestDetail-create','labTestDetail-index'])
                                <li class="nav-item {{request()->is('lab/labTestDetail*')?'menu-open':''}}">
                                    <a href="{{route('lab.labTestDetail.index')}}" class="nav-link {{request()->is('lab/labTestDetail*')?'active':''}}">
                                        <i class="nav-icon fas fa-prescription"></i>
                                        <p>
                                            Lab Test Detail
                                            {{--                                    <i class="right fas fa-angle-left"></i>--}}
                                        </p>
                                    </a>
                                    {{--                            <ul class="nav nav-treeview">--}}
                                    {{--                                @can('labTestDetail-create')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTestDetail.create')}}" class="nav-link {{request()->is('lab/labTestDetail/create')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>Add Lab Test Detail</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                                @can('labTestDetail-index')--}}
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a href="{{route('lab.labTestDetail.index')}}" class="nav-link {{request()->is('lab/labTestDetail')?'active':''}}">--}}
                                    {{--                                            <i class="far fa-circle nav-icon"></i>--}}
                                    {{--                                            <p>List Lab Test Detail</p>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                @endcan--}}
                                    {{--                            </ul>--}}
                                </li>
                            @endcan
                        </ul>
                    </li>
                    @can(['labReport-create','labReport-index'])
                        <li class="nav-item {{request()->is('lab/labReport')?'menu-open':''}}">
                            <a href="{{route('lab.labReport.index')}}" class="nav-link {{request()->is('lab/labReport')?'active':''}}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Lab Report
                                    {{--                            <i class="right fas fa-angle-left"></i>--}}
                                </p>
                            </a>
                            {{--                    <ul class="nav nav-treeview">--}}
                            {{--                        @can('labReport-create')--}}
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="{{route('lab.labReport.create')}}" class="nav-link {{request()->is('lab/labReport/create')?'active':''}}">--}}
                            {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                    <p>Add Lab Report</p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                        @endcan--}}
                            {{--                        @can('labReport-index')--}}
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="{{route('lab.labReport.index')}}" class="nav-link {{request()->is('lab/labReport')?'active':''}}">--}}
                            {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                    <p>List Lab Report</p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                        @endcan--}}
                            {{--                    </ul>--}}
                        </li>
                    @endcan
                @endcan

                <li class="nav-header">REPORT SECTION</li>
                @can(['patientRegistrationReport-index'])
                    <li class="nav-item">
                        <a href="{{route('report.patientRegistrationReport')}}" class="nav-link {{request()->is('report/patientRegistrationReport')?'active':''}}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Patient Registration Report
                            </p>
                        </a>
                    </li>
                @endcan
                @can(['ipdRegistrationReport-index'])
                    <li class="nav-item">
                        <a href="{{route('report.ipdRegistrationReport')}}" class="nav-link {{request()->is('report/ipdRegistrationReport')?'active':''}}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                IPDPatientRegistration Report
                            </p>
                        </a>
                    </li>
                @endcan
                @can(['billReport-index'])
                    <li class="nav-item">
                        <a href="{{route('report.billReport')}}" class="nav-link {{request()->is('report/billReport')?'active':''}}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Bill Report
                            </p>
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                       class="nav-link">
                        <i class="nav-icon fas fa-backspace"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
