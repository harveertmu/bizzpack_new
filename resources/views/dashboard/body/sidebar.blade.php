
<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading">Core</div>
            @if(Auth::user()->roles->pluck('name')[0]=='vendor')
            <a class="nav-link {{ Request::is('vendor-dashboard*') ? 'active' : '' }}" href="{{ url('/vendor-dashboard') }}">
            @elseif(Auth::user()->roles->pluck('name')[0]=='customer')
            <a class="nav-link {{ Request::is('vendor-dashboard*') ? 'active' : '' }}" href="{{ url('/vendor-dashboard') }}">

            @else
            <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            @endif
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>

            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")

            <a class="nav-link {{ Request::is('pos*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                POS
            </a>
            @endif

            <!-- Sidenav Heading (Orders)-->
            <div class="sidenav-menu-heading">Orders</div>
            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
            <a class="nav-link {{ Request::is('orders/complete*') ? 'active' : '' }}" href="{{ route('order.completeOrders') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-circle-check"></i></div>
                Complete
            </a>
            <a class="nav-link {{ Request::is('orders/pending*') ? 'active' : '' }}" href="{{ route('order.pendingOrders') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-clock"></i></div>
                Pending
            </a>
            <a class="nav-link {{ Request::is('orders/due*') ? 'active' : '' }}" href="{{ route('order.dueOrders') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-credit-card"></i></div>
                Due
            </a>
            @endif
            <!-- Sidenav Heading (Purchases)-->
            <div class="sidenav-menu-heading">Purchases</div>
            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
            <a class="nav-link {{ Request::is('purchases', 'purchase/create*', 'purchases/details*') ? 'active' : '' }}" href="{{ route('purchases.allPurchases') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                All
            </a>
            <a class="nav-link {{ Request::is('purchases/approved*') ? 'active' : '' }}" href="{{ route('purchases.approvedPurchases') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-circle-check"></i></div>
                Approval
            </a>
            <a class="nav-link {{ Request::is('purchases/report*') ? 'active' : '' }}" href="{{ route('purchases.dailyPurchaseReport') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-flag"></i></div>
                Daily Purchase Report
            </a>
            @endif

            <!-- Sidenav Accordion (Pages)-->
            {{-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                <div class="nav-link-icon"><i data-feather="grid"></i></div>
                People
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesCustomers">
                    <!-- Nested Sidenav Accordion (Pages -> People)-->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesCollapseCustomers" aria-expanded="false" aria-controls="pagesCollapseCustomers">
                        Customers
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseCustomers" data-bs-parent="#accordionSidenavPagesCustomers">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Show All</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="#">Customers</a>
                    <a class="nav-link" href="#">Suppliers</a>
                </nav>
            </div> --}}

            <!-- Sidenav Heading (Pages)-->
            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
            
            <div class="sidenav-menu-heading">Users Management</div>
            <a class="nav-link {{ Request::is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Customers
            </a>
            <a class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Suppliers
            </a>
            @endif

            <!-- Sidenav Heading (Products)-->
            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
            <div class="sidenav-menu-heading">Products</div>
            <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                Products
            </a>
            <!-- <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                Categories
            </a>
            <a class="nav-link {{ Request::is('units*') ? 'active' : '' }}" href="{{ route('units.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                Units
            </a> -->
            @endif

            <!-- Sidenav Heading (Settings)-->
            <div class="sidenav-menu-heading">Settings</div>
            <!-- <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Users
            </a> -->
            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
            <!-- <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Roles
            </a> -->
            @endif

            @if(Auth::user()->roles->pluck('name')[0]=="super-admin")
         <a class="nav-link {{ Request::is('product-code*') ? 'active' : '' }}" href="{{ route('product-code.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa fa-barcode"></i></div>
                Product Code
            </a> 
            @endif
        </div>
    </div>

    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ auth()->user()->name }} {{ Auth::user()->roles->pluck('name')[0] ?? '' }}

        </div>
    </div>
</nav>
