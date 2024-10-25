<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-px-150 h-auto">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboard</div>
                <span class="badge rounded-pill bg-danger ms-auto">5</span>
            </a>
        </li>

        <!-- POS -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">POS</span>
        </li>

        <!-- POS -->
        <li class="menu-item {{ Route::is('pos.index') ? 'active' : '' }}">
            <a href="{{ route('pos.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div class="text-truncate" data-i18n="POS">POS</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto"></div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>

        <!-- Customers -->
        <li class="menu-item {{ Route::is('customers.index') ? 'active' : '' }}">
            <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i> <!-- Always shows 'bx bx-user-circle' -->
                <div class="text-truncate" data-i18n="Customers">Customers</div>
            </a>
        </li>

        <!-- Products -->
        <li class="menu-item {{ Route::is('products.index') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat"></i> <!-- You can apply similar logic here if needed -->
                <div class="text-truncate" data-i18n="Products">Products</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto"></div>
            </a>
        </li>

        <!-- Invoices -->
        <li class="menu-item {{ Route::is('invoices.index') ? 'active' : '' }}">
            <a href="{{ route('invoices.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div class="text-truncate" data-i18n="Invoices">Invoices</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto"></div>
            </a>
        </li>



        <!-- Trial Balance -->
        <li class="menu-item {{ Route::is('trialbalance.index') ? 'active' : '' }}">
            <a href="{{ route('trialbalance.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate" data-i18n="Trial Balance">Trial Balance</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto"></div>
            </a>
        </li>

    </ul>


</aside>
