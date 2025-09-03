@extends('admin.layouts.app')
@section('content')
    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>

                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <!-- [Invoices Awaiting Payment] start -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body bg-warning">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                           <span style="color:#5d96fe  ">₹</span>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-white"><span
                                                    class="counter">{{ $universal_balance }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line text-white">Wallet Balance
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-4 col-md-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body bg-success">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                         <span style="color:#5d96fe  ">₹</span>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-white"><span
                                                    class="counter">{{ $payIn }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line text-white">Today's PayIn
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body bg-success">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            <span style="color:#5d96fe  ">₹</span>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-white"><span
                                                    class="counter">{{ $payOut }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line text-white">Today's PayOut
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @auth
                        @if(auth()->id() === 1)
                    <div class="col-xxl-4 col-md-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body bg-info">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            <span style="color:#5d96fe  ">₹</span>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-white"><span
                                                    class="counter">{{ $admin_payin_earning }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line text-white">Today's Payin Earning
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body bg-info">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            <span style="color:#5d96fe  ">₹</span>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-white"><span
                                                    class="counter">{{ $admin_payout_earning }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line text-white">Today's Payout Earning
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif
                    @endauth
                  </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            @if(auth()->user()->id ==1)
                            <div class="card-header">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-3">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date"
                                            placeholder="From Date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            placeholder="To Date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="customSearch" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="customSearch" name="customSearch"
                                            placeholder="Search...">
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <button id="filterBtn" class="btn btn-primary">Filter</button>
                                            <button id="resetBtn" class="btn btn-secondary">Reset</button>
                                            <select id="exportBtn" class="form-select w-auto">
                                                <option value="">Export</option>
                                                <option value="csv">CSV</option>
                                                <option value="pdf">PDF</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="RecentTable"
                                        data-url="{{ route('dashboard.data') }}">
                                        <thead>
                                            <tr>
                                                <th>Srno</th>
                                                <th>Transaction Id</th>
                                                <Th>type</Th>
                                                <Th>Amount</Th>
                                                <th>Balance</th>
                                                <th>Upload Type</th>
                                                <th>Description</th>
                                                <th>Status</th>

                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows will be loaded here by DataTable -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
            @push('script')
                <script>
                    $(document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        table = $('#RecentTable').DataTable({
                            processing: true,
                            serverSide: true,

                            ajax: {
                                url: $('#RecentTable').data('url'),
                                type: 'POST',
                                data: function(d) {
                                    // Add CSRF token and additional data
                                    d.from_date = $('input[name=from_date]').val();
                                    d.end_date = $('input[name=end_date]').val();
                                    d.search = $('input[name=customSearch]').val();

                                },
                                dataSrc: 'data' // Adjust based on your server response structure
                            },
                            paging: true,
                            pageLength: 5,
                            lengthChange: false,
                            searching: false,
                            order: [
                                [0, 'desc']
                            ], // 👈 default order (2nd column = transfer_by)
                            columns: [{
                                    data: 'srno',
                                    name: 'id',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'transaction_id',
                                    name: 'transaction_id'
                                },
                                {
                                    data: 'type',
                                    name: 'type'
                                },
                                {
                                    data: 'amount',
                                    name: 'amount'
                                },
                                {
                                    data: 'balance',
                                    name: 'balance'
                                },


                                {
                                    data: 'upload_type',
                                    name: 'upload_type'
                                },
                                {
                                    data: 'description',
                                    name: 'description'
                                },
                                  {
                                    data: 'status',
                                    name: 'status'
                                },
                                {
                                    data: 'created_at',
                                    name: 'created_at'
                                },
                            ]
                        });

                        $('#filterBtn').on('click', function(e) {
                            e.preventDefault();
                            table.ajax.reload();
                        });

                        // 🔹 Reset Button Click
                        $('#resetBtn').on('click', function(e) {
                            e.preventDefault();
                            $('#from_date').val('');
                            $('#end_date').val('');
                            $('#customSearch').val('');
                            table.ajax.reload();
                        });

                        $('#exportBtn').on('change', function() {
                            let format = $(this).val(); // csv / pdf
                            if (!format) return;

                            // Collect filter values
                            let params = {
                                from_date: $('#from_date').val(),
                                end_date: $('#end_date').val(),
                                search: $('#customSearch').val(),
                                format: format,
                                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                            };

                            // Create a temporary form for POST
                            let form = $('<form>', {
                                action: "{{ route('dashboard.export') }}",
                                method: 'POST',
                                target: '_blank' // open in new tab (optional)
                            });

                            // Append hidden inputs
                            $.each(params, function(key, value) {
                                form.append($('<input>', {
                                    type: 'hidden',
                                    name: key,
                                    value: value
                                }));
                            });

                            // Append form to body and submit
                            form.appendTo('body').submit().remove();

                            $(this).val(''); // reset select
                        });


                    });
                </script>
            @endpush
        </div>

        @include('admin.layouts.footer')
        <!-- [ Footer ] end -->
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
    @include('admin.layouts.message')
@endsection
