<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('images/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
                <a href="#">
                <i class="fa fa-th"></i>
                <span>Products</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/products"><i class="fa fa-circle-o"></i> List Of Products</a></li>
                    <li><a href="/products/create"><i class="fa fa-circle-o"></i> Add New Product</a></li>
                    <li><a href="/uom"><i class="fa fa-circle-o"></i> Units of Measure</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-cart-arrow-down"></i>
                <span>Sales</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/sales"><i class="fa fa-circle-o"></i> Sales Orders</a></li>
                    @ability('admin,owner','')
                    <li><a href="/bills"><i class="fa fa-circle-o"></i> List Of Bills</a></li>
                    @endability
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-users"></i>
                <span>Customers</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/customers"><i class="fa fa-circle-o"></i> List of Customers</a></li>
                    <li><a href="/customers/create"><i class="fa fa-circle-o"></i> Add New Customer</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-users"></i>
                <span>Suppliers</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/suppliers"><i class="fa fa-circle-o"></i> List of Suppliers</a></li>
                    <li><a href="/suppliers/create"><i class="fa fa-circle-o"></i> Add New Supplier</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-cart-plus"></i>
                <span>Purchases</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/purchases"><i class="fa fa-circle-o"></i> Purchase Orders</a></li>
                    <li><a href="/purchases/create"><i class="fa fa-circle-o"></i> Create New Purchase Order</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-bar-chart"></i>
                <span>Reports</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/reporting/sales"><i class="fa fa-circle-o"></i> Sales Analysis</a></li>
                    <li><a href="/reporting/purchases"><i class="fa fa-circle-o"></i> Purchase Analysis</a></li>
                    <li><a href="/reporting/stock"><i class="fa fa-circle-o"></i> {{ trans('navigation.stock_valuation') }}</a></li>
                </ul>
            </li>  
            @role('admin')
           <li class="treeview">
                <a href="#">
                <i class="fa fa-users"></i>
                <span>{{ trans('navigation.users') }}</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/users"><i class="fa fa-circle-o"></i> {{ trans('navigation.list_of_users') }}</a></li>
                    <li><a href="/roles"><i class="fa fa-circle-o"></i> {{ trans('navigation.list_of_roles') }}</a></li>
                    <li><a href="/permissions"><i class="fa fa-circle-o"></i> {{ trans('navigation.permissions') }}</a></li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                <i class="fa fa-cogs"></i>
                <span>Configuration </span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                   
                    <li><a href="/settings"><i class="fa fa-circle-o"></i> Business Details</a></li>
                </ul>
            </li>
            @endrole
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
