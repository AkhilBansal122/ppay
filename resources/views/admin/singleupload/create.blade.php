@extends('admin.layouts.app')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Single Upload</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Add New</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="{{ route('dashboard') }}" class="page-header-right-close-toggle">
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

            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body lead-status">
                               <form action="{{ route('singleupload.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <!-- Select Transfer By -->
        <div class="col-lg-4 mb-4">
            <label for="transfer_by" class="form-label">
                Select Transfer By <span class="text-danger">*</span>
            </label>
            <select name="transfer_by" id="transfer_by" class="form-select form-select-solid" required>
                <option value="">Select Transfer Method</option>
                <option value="bank" {{ old('transfer_by') == 'bank' ? 'selected' : '' }}>Bank</option>
            </select>
            <span class="text-danger">{{ $errors->first('transfer_by') }}</span>
        </div>

        <!-- Account Number -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Account Number <span class="text-danger">*</span></label>
            <input type="text" required name="account_number" value="{{ old('account_number') }}"
                   placeholder="Enter account Number" class="form-control" />
            <span class="text-danger">{{ $errors->first('account_number') }}</span>
        </div>

        <!-- Account Holder Name -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
            <input type="text" required name="account_holder_name" value="{{ old('account_holder_name') }}"
                   placeholder="Enter Account Holder Name" class="form-control" />
            <span class="text-danger">{{ $errors->first('account_holder_name') }}</span>
        </div>

        <!-- Bank IFSC Code -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Bank IFSC Code <span class="text-danger">*</span></label>
            <input type="text" required name="ifsc" value="{{ old('ifsc') }}"
                   placeholder="Enter IFSC" class="form-control" />
            <span class="text-danger">{{ $errors->first('ifsc') }}</span>
        </div>

        <!-- Bank Name -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
            <input type="text" required name="bank_name" value="{{ old('bank_name') }}"
                   placeholder="Enter Bank Name" class="form-control" />
            <span class="text-danger">{{ $errors->first('bank_name') }}</span>
        </div>

        <!-- Transfer Amount -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Transfer Amount <span class="text-danger">*</span></label>
            <input type="number" required step="0.01" name="transfer_amount" value="{{ old('transfer_amount') }}"
                   placeholder="Enter transfer amount" class="form-control" />
            <span class="text-danger">{{ $errors->first('transfer_amount') }}</span>
        </div>

        <!-- Payment Mode -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
            <select name="payment_mode" class="form-select form-select-solid" required>
                <option value="">Select Payment Mode</option>
                <option value="NEFT" {{ old('payment_mode') == 'NEFT' ? 'selected' : '' }}>NEFT</option>
                <option value="IMPS" {{ old('payment_mode') == 'IMPS' ? 'selected' : '' }}>IMPS</option>
                <option value="RTGS" {{ old('payment_mode') == 'RTGS' ? 'selected' : '' }}>RTGS</option>
            </select>
            <span class="text-danger">{{ $errors->first('payment_mode') }}</span>
        </div>

        <!-- Remark -->
        <div class="col-lg-4 mb-4">
            <label class="form-label">Remark</label>
            <input type="text" name="remark" value="{{ old('remark') }}"
                   placeholder="Enter remark" class="form-control" />
            <span class="text-danger">{{ $errors->first('remark') }}</span>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Back Button -->
        <div class="col-lg-4 mb-4 d-flex justify-content-start">
            <a href="{{ route('singleupload.index') }}" class="btn btn-secondary">
                <i class="feather-arrow-left me-2"></i> Back
            </a>
        </div>

        <!-- Save Button -->
        <div class="col-lg-4 mb-4 offset-lg-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="feather-save me-2"></i> Submit
            </button>
        </div>
    </div>
</form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
