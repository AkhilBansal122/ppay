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
                        <h5 class="m-b-10"> Wallet Topup Requests</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"> Wallet Topup Requests</li>
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

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body with Table --}}
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="requestTable"
                                        data-url="{{ route('wallet-topup-request.data') }}">
                                        <thead>
                                            <tr>
                                                <th>Srno</th>
                                                <th>User Name</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>platform_charge</th>
                                                <th>gst</th>
                                                <th>Requested By</th>
                                                <th>Status</th>

                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- DataTables will populate this -->
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>



            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


            @push('script')
                <script>
                    $(document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        const table = $('#requestTable').DataTable({
                            processing: true,
                            serverSide: true,
                            dom: 'Bfrtip',
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
                                url: $('#requestTable').data('url'),
                                type: 'POST',
                                data: function(d) {
                                    d.from_date = $('#from_date').val();
                                    d.end_date = $('#end_date').val();
                                    d.search = $('#customSearch').val();
                                },
                                dataSrc: 'data'
                            },
                            pageLength: 5,
                            lengthChange: false,
                            searching: false,
                            order: [
                                [0, 'desc']
                            ], // created_at
                            columns: [{
                                    data: 'srno',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'name',
                                      orderable: false,
                                    name: 'name'
                                },
                                {
                                    data: 'amount',
                                    searchable: false,
                                    name: 'amount'
                                },
                                {
                                    data: 'remark',
                                    name: 'remark'
                                },
                                {
                                    data: 'platform_charge',
                                    name: 'platform_charge'
                                },
                                {
                                    data: 'gst',
                                    name: 'gst'
                                },


                                {
                                    data: 'requested_by',
                                     searchable: false,
                                    name: 'requested_by'
                                },
                                {
                                    data: 'status',
                                    name: 'status'
                                },

                                {
                                    data: 'created_at',
                                    name: 'created_at'
                                },

                                {
                                    data: 'action',
                                    name: 'action'
                                },

                            ]
                        });

                        $('#filterBtn').on('click', function(e) {
                            e.preventDefault();
                            table.ajax.reload();
                        });

                        $('#resetBtn').on('click', function(e) {
                            e.preventDefault();
                            $('#from_date, #end_date, #customSearch').val('');
                            table.ajax.reload();
                        });

                        $('#exportBtn').on('change', function() {
                            const format = $(this).val();
                            if (!format) return;

                            const params = {
                                from_date: $('#from_date').val(),
                                end_date: $('#end_date').val(),
                                search: $('#customSearch').val(),
                                format: format,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            };

                            const form = $('<form>', {
                                action: "{{ route('wallet-topup-request.export') }}", // 👈 your correct export route
                                method: 'POST',
                                target: '_blank'
                            });

                            $.each(params, function(key, value) {
                                form.append($('<input>', {
                                    type: 'hidden',
                                    name: key,
                                    value: value
                                }));
                            });

                            form.appendTo('body').submit().remove();
                            $(this).val(''); // reset select
                        });
                    });

                    function updateWalletRequestStatus(id, status,requested_user_id) {
                     //   alert("Request ID: " + id + "\nStatus: " + status); // Debug alert

                        // $('#model_user_id').val(0);
                        var CSRF_TOKEN = '{{ csrf_token() }}';

                        if (status === 'DECLINED') {
                            if (confirm("Are you sure you want to decline this request? This action cannot be undone.")) {
                                $.post("{{ route('wallet-topup-request.updateWalletRequestStatus') }}", {
                                    _token: CSRF_TOKEN,
                                    id: id,
                                    status: status
                                }, function(res) {
                                    res = JSON.parse(res);
                                    if (res.status) {
                                        alert("Request has been declined successfully.");
                                        window.location.reload();
                                    } else {
                                        alert("Error: " + res.message);
                                    }
                                });
                            }
                        } else if (status === 'APPROVED') {
                            if (confirm("Are you sure you want to approved?")) {
                                 $.post("{{ route('wallet-topup-request.updateWalletRequestStatus') }}", {
                                    _token: CSRF_TOKEN,
                                         id: id,
                                        utr_no: `SBIN123456789${id}`, // Template literal
                                        status: 'APPROVED'            // Correct assignment

                                }, function(res) {
                                    res = JSON.parse(res);
                                console.log("res,res",res);
                                    if (res.status) {
                                        alert("Request has been Approved.");
                                        window.location.reload();
                                    } else {
                                        alert("Error: " + res.message);
                                    }
                                })
                            }
                        ///    $('#model_user_id').val(id);
                            // $('#approveModel').modal('show');
                        } else if (status === 'REVERTED') {

                                        // var id =requested_user_id;

                            if (confirm("Are you sure you want to reverted?")) {
                                 $.post("{{ route('wallet-topup-request.updateWalletRequestStatus') }}", {
                                    _token: CSRF_TOKEN,
                                         id: id,
                                        remark:"REVERTED",status:'REVERTED'

                                }, function(res) {
                                    res = JSON.parse(res);
                              //  console.log("res,res",res);
                                    if (res.status) {
                                        alert("Request has been Reverted.");
                                        window.location.reload();
                                    } else {
                                        alert("Error: " + res.message);
                                    }
                                })
                            }

                        } else if (status === 'RETRY') {
                            if (confirm("Are you sure you want to retry this request?")) {
                                $.post("{{ route('wallet-topup-request.updateWalletRequestStatus') }}", {
                                    _token: CSRF_TOKEN,
                                    id: id,
                                    status: status
                                }, function(res) {
                                    res = JSON.parse(res);
                                    if (res.status) {
                                        alert("Request has been retried successfully.");
                                        window.location.reload();
                                    } else {
                                        alert("Error: " + res.message + "\nAPI Response: " + res.api_response);
                                    }
                                });
                            }
                        }
                    }


                    $("#requestApprove").click(function() {
                        var utr_no = $("#utr_no").val();
                        var CSRF_TOKEN = '{{ csrf_token() }}';
                        if (utr_no == null || utr_no == '') {
                            // $("#errorMsg").show();
                        } else {
                            $("#errorMsg").hide();
                            var id = $('#model_user_id').val();
                            $('#approveModel').modal('hide');
                            $('.action-' + id).remove();
                            $.ajax({
                                url: "{{ route('wallet-topup-request.updateWalletRequestStatus') }}",
                                cache: false,
                                type: "POST",
                                data: {
                                    _token: CSRF_TOKEN,
                                    id: id,
                                    utr_no: "1234567",
                                    status: 'APPROVED'
                                },
                                success: function(res) {
                                    res = JSON.parse(res);
                                    if (res.status) {
                                        Swal.fire(
                                            "Approved!",
                                            "Request has been Approved.",
                                            "success"
                                        ).then(function(result) {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            "Error!",
                                            res.message,
                                            "error"
                                        )
                                    }

                                }
                            });
                        }


                    })
                    $("#requestRevert").click(function() {
                        var remark = $("#remark").val();
                        var CSRF_TOKEN = '{{ csrf_token() }}';
                        if (remark == null || remark == '') {
                            $("#revert_errorMsg").show();
                        } else {
                            $("#revert_errorMsg").hide();
                            var id = $('#revert_user_id').val();
                            $('#revertModel').modal('hide');
                            $('.action-' + id).remove();
                            $.ajax({
                                url: "{{ route('wallet-topup-request.updateWalletRequestStatus') }}",
                                cache: false,
                                type: "POST",
                                data: {
                                    _token: CSRF_TOKEN,
                                    id: id,
                                    remark: remark,
                                    status: 'REVERTED'
                                },
                                success: function(res) {
                                    res = JSON.parse(res);
                                    if (res.status) {
                                        Swal.fire(
                                            "Reverted!",
                                            "Request has been Reverted.",
                                            "success"
                                        ).then(function(result) {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            "Error!",
                                            res.message,
                                            "error"
                                        )
                                    }

                                }
                            });
                        }


                    })
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
