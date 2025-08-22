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
                        <h5 class="m-b-10">{{ $title }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">{{ $title }}</li>
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



            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                       <div class="card-header">
    <form method="POST" action="{{ route('service-charge.store') }}">
        @csrf
        <div class="row align-items-end g-3">
            {{-- User Selection --}}
            <div class="col-md-3">
                <label for="user_id" class="form-label">User</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->first_name }} {{ $user->last_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div class="col-md-2">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="PAYIN">PayIn</option>
                    <option value="PAYOUT">Payout</option>
                </select>
            </div>

            {{-- Commission Percentage --}}
            <div class="col-md-2">
                <label for="comission_percentage" class="form-label">Commission %</label>
                <input type="number" step="0.01" class="form-control" id="comission_percentage"
                    name="comission_percentage" placeholder="%" required>
            </div>

            {{-- Commission Amount --}}
            <div class="col-md-2">
                <label for="comission_amount" class="form-label">Commission Amount</label>
                <input type="number" step="0.01" class="form-control" id="comission_amount"
                    name="comission_amount" placeholder="₹" required>
            </div>

            {{-- Submit --}}
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Add Commission</button>
            </div>
        </div>
    </form>
</div>



                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="RecentTable"
                                    data-url="{{ route('service-charge.data') }}">
                                    <thead>
                                        <tr>
                                            <th>Srno</th>
                                            <Th>Ref ID</Th>
                                            <Th>Ref Type</Th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Charge</th>
                                            <th>Total PayIn</th>
                                            <th>Is Charged</th>
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
                            [1, 'desc']
                        ], // 👈 default order (2nd column = transfer_by)
                        columns: [{
                                data: 'srno',
                                name: 'id',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'ref_id',
                                name: 'ref_id'
                            },
                            {
                                data: 'ref_type',
                                name: 'ref_type'
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
                                data: 'charge',
                                name: 'charge'
                            },
                            {
                                data: 'total_charge',
                                name: 'total_charge'
                            },
                            {
                                data: 'is_charged',
                                name: 'is_charged'
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
