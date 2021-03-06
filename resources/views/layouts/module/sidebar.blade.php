<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="POS" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">POS</span>
    </a>
    ​
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Daengweb.id</a>
            </div>
        </div>
        ​
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview menu-open">
                    <a href="{{route('home')}}" class="nav-link {{request()->is('home') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @role('admin')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{Request::is('users*') || Request::is('role*') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Manajemen Users
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('users.index')}}" class="nav-link {{Request::route()->getName() =='users.index' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('role.index')}}" class="nav-link {{Request::route()->getName() == 'role.index' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Role</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.roles_permission')}}" class="nav-link {{Request::route()->getName() == 'users.roles_permission' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Role Permission</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @if(Auth::user()->can('manage products'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{Request::is('product*') || Request::is('category*') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-server"></i>
                        <p>
                            Manajemen Produk
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link {{Request::route()->getName() == 'category.index' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('product.index')}}" class="nav-link {{Request::route()->getName() == 'product.index' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @role('kasir')
                <li class="nav-item">
                    <a href="{{route('order.transaksi')}}" class="nav-link {{Request::route()->getName() == 'order.transaksi' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>
                                Transaksi
                            </p>
                    </a>
                </li>
                @endrole
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{Request::is('order*') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-shopping-bag"></i>
                        <p>Manajemen Order
                        <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('order.index')}}" class="nav-link {{Request::route()->getName() == 'order.index' ? 'active' : ''}}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Order</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="{{route('logout')}}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-sign-out"></i>
                        <p>{{__('Logout')}}</p>
                        </a>
                    <form id="logout-form" action="{{route('logout')}}"
                          method="post" style="display: none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
