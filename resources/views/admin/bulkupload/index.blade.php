@extends('admin.layouts.app')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Bulk Upload</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Bulk Upload</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="{{ route('bulkUpload.index') }}" class="page-header-right-close-toggle">
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
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body lead-status">

                                {{-- Centered Title --}}
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold">Bulk Upload</h4>
                                </div>

                                <form action="{{ route('bulkUpload.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row justify-content-center">
                                        <!-- File Upload -->
                                        <div class="col-lg-6 mb-4 text-center">
                                            <label class="form-label">Upload CSV File <span
                                                    class="text-danger">*</span></label>


                                            <input type="file" name="file" required class="form-control text-center"
                                                accept=".csv, .xls, .xlsx" />

                                            <span class="text-danger">{{ $errors->first('file') }}</span>

                                            {{-- Sample Download Link --}}
                                            @if (session('sample_file'))
                                                <div class="mt-2">
                                                    <a href="{{ session('sample_file') }}" class="btn btn-link" download>
                                                        📥 Download Sample CSV Format
                                                    </a>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <a href="{{ asset('/public/uploads/samples/sample_bulk_upload.csv') }}"
                                                        class="btn btn-link" download>
                                                        📥 Download Sample CSV Format
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4 justify-content-center">
                                        <div class="col-lg-4 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-save me-2"></i> Upload File
                                            </button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="mainBulkUploadTable"
                                        data-url="{{ route('bulkUpload.data') }}">
                                        <thead>
                                            <tr>
                                                <th>Srno</th>
                                                <th>Transfer By</th>
                                                <Th>account holder name</Th>
                                                <Th>account number</Th>
                                                <th>bank name</th>
                                                <th>ifsc</th>
                                                <th>transfer amount</th>
                                                <th>payment mode</th>
                                                <th>remark</th>
                                                <th>status</th>
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
        </div>

        @push('script')
            <script>
                $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#mainBulkUploadTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: $('#mainBulkUploadTable').data('url'),
                            type: 'POST',
                            data: function(d) {
                                // Add CSRF token and additional data
                                d.from_date = $('input[name=from_date]').val();
                                d.end_date = $('input[name=end_date]').val();
                            },
                            dataSrc: 'data' // Adjust based on your server response structure
                        },
                        paging: true,
                        pageLength: 5,
                        lengthChange: false,
                        searching: true,
                         order: [[1, 'desc']], // 👈 default order (2nd column = transfer_by)
                        columns: [{
                                data: 'srno',
                                name: 'id',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'transfer_by',
                                name: 'transfer_by'
                            },
                            {
                                data: 'bank_name',
                                name: 'bank_name'
                            },
                            {
                                data: 'account_number',
                                name: 'account_number'
                            },
                            {
                                data: 'account_holder_name',
                                name: 'account_holder_name'
                            },
                            {
                                data: 'ifsc',
                                name: 'ifsc'
                            },
                            {
                                data: 'transfer_amount',
                                name: 'transfer_amount'
                            },

                            {
                                data: 'payment_mode',
                                name: 'payment_mode'
                            },
                            {
                                data: 'remark',
                                name: 'remark'
                            },
                            {
                                data:"status",
                                name:"status"
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                        ]
                    });

                    // Status change event
                    $(document).on("change", ".statusChange", function() {
                        var dataurl = $(this).data("url");
                        var id = $(this).data('id');
                        //  var newStatus = $(this).val();
                        var newStatus = this.checked ? 1 : 0;
                        $.ajax({
                            url: dataurl,
                            type: 'POST',
                            data: {
                                id: id,
                                status: newStatus
                            },
                            success: function(response) {
                                // Handle success response
                                $('#mainBulkUploadTable').DataTable().ajax.reload(null,
                                    false); // Reload the table without resetting the pagination
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                alert("Failed to update status. Please try again.");
                            }
                        });
                    });
                });
            </script>
        @endpush

        <!-- [ Footer ] start -->
        <!-- [ Footer ] end -->
        @include('admin.layouts.footer')
    </main>
@endsection
