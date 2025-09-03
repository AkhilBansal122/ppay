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
                        <h5 class="m-b-10">Ledgers</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Ledgers</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        {{-- <div class="d-flex d-md-none">
                        <a href="{{ route('payins') }}" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div> --}}

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
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">

                            {{-- Card Header with Filters --}}
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
                                            @if (auth()->user()->id == 1)
                                                <select id="StatusBtn" name="StatusBtn" class="form-select w-auto">
                                                    <option value="">Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="success">Success </option>
                                                    <option value="failed">Failed</option>

                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Card Body with Table --}}
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="ledgersTable"
                                        data-url="{{ route('ledgers.data') }}">
                                        <thead>
                                            <tr>
                                                <th>Srno</th>
                                                <th>Transaction Id</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                                <th>Upload Type</th>
                                                <th>Description</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- DataTables will load rows here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            @push('script')
                <script>
                    $(document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        table = $('#ledgersTable').DataTable({
                            processing: true,
                            serverSide: true,
                            dom: 'Bfrtip', // 👈 Needed for export buttons
                            buttons: [{
                                    extend: 'csvHtml5',
                                    text: 'Export CSV',
                                    className: 'btn btn-success btn-sm'
                                },
                                {
                                    extend: 'pdfHtml5',
                                    text: 'Export PDF',
                                    className: 'btn btn-danger btn-sm'
                                }
                            ],
                            ajax: {
                                url: $('#ledgersTable').data('url'),
                                type: 'POST',
                                data: function(d) {
                                    // Add CSRF token and additional data
                                    d.statusValue = $('input[name=StatusBtn]').val();

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
                                [1, 'desc']
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
                                    data: 'status',
                                    name: 'status'
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
                                    data: 'created_at',
                                    name: 'created_at'
                                },
                            ]
                        });
                        // 🔹 Filter Button Click
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
                                action: "{{ route('ledgers.export') }}",
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
                        $('#StatusBtn').on('change', function(e) {
                            e.preventDefault();
                            table.ajax.reload();
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
