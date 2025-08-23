<!--! ================================================================ !-->
<!--! [Start] Navigation Manu !-->
<!--! ================================================================ !-->
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="index.html" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/logo-full.jpeg' }}" alt=""
                    class="logo logo-lg" />
                <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/logo-abbr.jpeg' }}" alt=""
                    class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li class="nxl-item nxl-hasmenu {{ request()->is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboards</span>
                    </a>
                </li>

                @if (auth()->check() && auth()->user()->id == 1)
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Role/Permission</span><span class="nxl-arrow"><i
                                    class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu"
                            style="display: {{ request()->is('admin.permissions/*') || request()->is('roles/*') ? 'block' : 'none' }};">
                            @can('Role-Management')
                                <li class="nxl-item {{ request()->is('roles/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('roles.index') }}">Roles</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @can('Users-Management')
                    <li class="nxl-item nxl-hasmenu {{ request()->is('users/*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-users"></i></span>
                            <span class="nxl-mtext">Users Management</span>
                        </a>
                    </li>
                @endcan
                @if (auth()->check() && auth()->user()->id != 1)
                    <li
                        class="nxl-item nxl-hasmenu nxl-trigger {{ request()->is('singleupload/*') || request()->is('bulkUpload/*') || request()->is('some-route') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Uploads</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu"
                            style="display: {{ request()->is('admin/singleupload*') || request()->is('admin/bulkUpload*') ? 'block' : 'none' }};">

                            @can('Single-Upload')
                                <li class="nxl-item {{ request()->is('singleupload/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('singleupload.index') }}">Single Upload</a>
                                </li>
                            @endcan

                            @can('Bulk-Upload')
                                <li class="nxl-item {{ request()->is('admin/bulkUpload/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('bulkUpload.index') }}">Bulk Upload</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('Transations')

                    <li
                        class="nxl-item nxl-hasmenu nxl-trigger {{ request()->is('payin/*') || request()->is('payout/*') || request()->is('some-route') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Transaction</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu"
                            style="display: {{ request()->is('admin/payins*') || request()->is('admin/ledgers*') || request()->is('admin/payouts*') ? 'block' : 'none' }};">

                            @can('PayIn')
                                <li class="nxl-item {{ request()->is('payins/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('payins.index') }}">PayIn</a>
                                </li>
                            @endcan

                            @can('PayOut')
                                <li class="nxl-item {{ request()->is('admin/payouts/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('payouts.index') }}">Payout</a>
                                </li>
                            @endcan
                            @can('Ledger')
                                <li class="nxl-item {{ request()->is('ledgers/*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('ledgers.index') }}">Ledger</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('wallet-topup')
                    <li
                        class="nxl-item nxl-hasmenu nxl-trigger
        {{ request()->is('wallet-topup-request*') || request()->is('wallet-topup*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Wallets</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu"
                            style="display:
                {{ request()->is('wallet-topup/*') || request()->is('wallet-topup-request*') ? 'block' : 'none' }};">


                            @can('wallet-topup')
                                <li class="nxl-item {{ request()->is('wallet-topup*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('wallet-topup.index') }}">Wallet Top</a>
                                </li>
                            @endcan
                            @can('wallet-topup-request')
                                <li class="nxl-item {{ request()->is('wallet-topup-request*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('wallet-topup-request.index') }}">Wallet Topup
                                        Request</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('API-Doc')
                    <li class="nxl-item nxl-hasmenu {{ request()->is('admin.apiDoc') ? 'active' : '' }}">
                        <a href="{{ route('apiDoc') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Api Doc</span>
                        </a>
                    </li>
                @endcan
                     @can('service-charges')
                    <li style="display: none"
                        class="nxl-item nxl-hasmenu nxl-trigger
        {{ request()->is('service-charge*') || request()->is('service-charges/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Service Charges</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu"
                            style="display:
                {{ request()->is('service-charge/*') || request()->is('commission*') ? 'block' : 'none' }};">


                            @can('service-charges')
                              <li class="nxl-item {{ request()->is('service-charge*') ? 'active' : '' }}">
                        <a href="{{ route('service-charge') }}" class="nxl-link">
                            <span class="nxl-mtext">Charges</span>
                        </a>
                    </li>
                            @endcan
                            @can('commission')
                                <li class="nxl-item {{ request()->is('commission*') ? 'active' : '' }}">
                                    <a class="nxl-link" href="{{ route('commission.index') }}">Commission</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan




            </ul>
        </div>
    </div>
</nav>

<!--! ================================================================ !-->
<!--! [End]  Navigation Manu !-->
<!--! ================================================================ !-->
