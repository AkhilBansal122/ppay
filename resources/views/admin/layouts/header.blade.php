<!--! ================================================================ !-->
<!--! [Start] Header !-->
<!--! ================================================================ !-->
<header class="nxl-header">
    <style>
        .feather-align-left,.nxl-head-link{
            color:#fff !important
        }
    </style>
    <div class="header-wrapper" style="background:#5d96fe;!importent;color:white">
        <!--! [Start] Header Left !-->
        <div class="header-left d-flex align-items-center gap-4">
            <!--! [Start] nxl-head-mobile-toggler !-->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!--! [Start] nxl-head-mobile-toggler !-->
            <!--! [Start] nxl-navigation-toggle !-->
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            <!--! [End] nxl-navigation-toggle !-->
            <!--! [Start] nxl-lavel-mega-menu-toggle !-->
            <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                    <i class="feather-align-left"></i>
                </a>
            </div>
            <!--! [End] nxl-lavel-mega-menu-toggle !-->
            <!--! [Start] nxl-lavel-mega-menu !-->
            <div class="nxl-drp-link nxl-lavel-mega-menu">
                <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                    <a href="javascript:void(0)" id="nxl-lavel-mega-menu-hide">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
                <!--! [Start] nxl-lavel-mega-menu-wrapper !-->
                <div class="nxl-lavel-mega-menu-wrapper d-flex gap-3">
                    <!--! [Start] nxl-lavel-menu !-->

                    <!--! [End] nxl-lavel-menu !-->
                    <!--! [Start] nxl-h-item nxl-mega-menu !-->

                    <!--! [End] nxl-h-item nxl-mega-menu !-->
                </div>
                <!--! [End] nxl-lavel-mega-menu-wrapper !-->
            </div>
            <!--! [End] nxl-lavel-mega-menu !-->
        </div>
        <!--! [End] Header Left !-->
        <!--! [Start] Header Right !-->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">


                <div class="dropdown nxl-h-item " style="display: none">
                    <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button"
                        data-bs-auto-close="outside">
                        <i class="feather-bell"></i>
                        <span class="badge bg-danger nxl-h-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                        <div class="d-flex justify-content-between align-items-center notifications-head">
                            <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                            <a href="javascript:void(0);" class="fs-11 text-success text-end ms-auto"
                                data-bs-toggle="tooltip" title="Make as Read">
                                <i class="feather-check"></i>
                                <span>Make as Read</span>
                            </a>
                        </div>
                        <div class="notifications-item">
                            <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/avatar/2.png'}}" alt="" class="rounded me-3 border" />
                            <div class="notifications-desc">
                                <a href="javascript:void(0);" class="font-body text-truncate-2-line"> <span
                                        class="fw-semibold text-dark">Malanie Hanvey</span> We should talk about that at
                                    lunch!</a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="notifications-date text-muted border-bottom border-bottom-dashed">2
                                        minutes ago</div>
                                    <div class="d-flex align-items-center float-end gap-2">
                                        <a href="javascript:void(0);"
                                            class="d-block wd-8 ht-8 rounded-circle bg-gray-300"
                                            data-bs-toggle="tooltip" title="Make as Read"></a>
                                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip"
                                            title="Remove">
                                            <i class="feather-x fs-12"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notifications-item">
                            <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/avatar/3.png'}}" alt="" class="rounded me-3 border" />
                            <div class="notifications-desc">
                                <a href="javascript:void(0);" class="font-body text-truncate-2-line"> <span
                                        class="fw-semibold text-dark">Valentine Maton</span> You can download the latest
                                    invoices now.</a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="notifications-date text-muted border-bottom border-bottom-dashed">36
                                        minutes ago</div>
                                    <div class="d-flex align-items-center float-end gap-2">
                                        <a href="javascript:void(0);"
                                            class="d-block wd-8 ht-8 rounded-circle bg-gray-300"
                                            data-bs-toggle="tooltip" title="Make as Read"></a>
                                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip"
                                            title="Remove">
                                            <i class="feather-x fs-12"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notifications-item">
                            <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/avatar/4.png'}}" alt="" class="rounded me-3 border" />
                            <div class="notifications-desc">
                                <a href="javascript:void(0);" class="font-body text-truncate-2-line"> <span
                                        class="fw-semibold text-dark">Archie Cantones</span> Don't forget to pickup
                                    Jeremy after school!</a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="notifications-date text-muted border-bottom border-bottom-dashed">53
                                        minutes ago</div>
                                    <div class="d-flex align-items-center float-end gap-2">
                                        <a href="javascript:void(0);"
                                            class="d-block wd-8 ht-8 rounded-circle bg-gray-300"
                                            data-bs-toggle="tooltip" title="Make as Read"></a>
                                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip"
                                            title="Remove">
                                            <i class="feather-x fs-12"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center notifications-footer">
                            <a href="javascript:void(0);" class="fs-13 fw-semibold text-dark">Alls Notifications</a>
                        </div>
                    </div>
                </div>

                <div class="nxl-h-item dark-light-theme">
                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                        <i class="feather-moon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </a>
                </div>

                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/avatar/avtar.png'}}" alt="user-image" class="img-fluid user-avtar me-0" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="{{ config('custom.public_path') . '/adminAssets/assets/images/avatar/avtar.png'}}" alt="user-image" class="img-fluid user-avtar" />
                                <div>
                                    <h6 class="text-dark mb-0">{{ auth()->user()->first_name }}
                                    <span class="fs-12 fw-medium text-muted">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="dropdown">
                                <span class="hstack">
                                    <i
                                        class="wd-10 ht-10 border border-2 border-gray-1 bg-success rounded-circle me-2"></i>
                                    <span>Active</span>
                                </span>

                            </a>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--! [End] Header Right !-->
    </div>
</header>
<!--! ================================================================ !-->
<!--! [End] Header !-->
<!--! ================================================================ !-->
