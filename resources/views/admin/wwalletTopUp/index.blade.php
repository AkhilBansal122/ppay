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
                        <h5 class="m-b-10"> Wallet Topup </h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Request for  Wallet Topup </li>
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


                <form method="POST" action="{{ route('wallet-topup.store') }}">
                    @csrf
                    <div class="card-header">
                        <div class="row align-items-end g-2">

                            <div class="row">
                            <div class="col-md-3">
                                <label for="user_id" class="form-label">User</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">-- Select User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->first_name }}) ({{ $user->last_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" required id="amount" name="amount"
                                    placeholder="Enter amount">
                            </div>

                             <div class="col-md-3">
                                <label for="utrnumber" class="form-label">UTR Number</label>
                                <input type="text" class="form-control" required id="utrnumber" name="utrnumber"
                                    placeholder="Enter UTR No.">
                            </div>

                            <div class="col-md-2">
                                <label for="remark" class="form-label">Remark</label>
                                <input type="text" class="form-control" id="remark" name="remark" value="WALLET LOAD" readonly
                                    placeholder="Enter remark">
                            </div>
                            <div class="col-md-1 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Request</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        @include('admin.layouts.footer')
        <!-- [ Footer ] end -->
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
    @include('admin.layouts.message')
@endsection
