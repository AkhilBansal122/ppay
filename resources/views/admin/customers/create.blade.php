@extends('admin.layouts.app')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">User</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Add New</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="{{ route('users.index') }}" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <spann>Back</spann>
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
                                <form action="{{ route('users.store') }}" id="permissionForm" method="POST">
                                    @csrf
                                    <div class="row border rounded p-3 mb-4">
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Username <span style="color:red">*</span></label>
                                            <input type="text" required name="name" value="{{ old('name') }}"
                                                placeholder="Please enter a username" class="form-control" />
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">First Name <span style="color:red">*</span></label>
                                            <input type="text" required name="first_name" value="{{ old('first_name') }}"
                                                placeholder="Please enter a first name" class="form-control" />
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Last Name<span style="color:red">*</span></label>
                                            <input type="text" required name="last_name" value="{{ old('last_name') }}"
                                                placeholder="Please enter a last name" class="form-control" />
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Email<span style="color:red">*</span></label>
                                            <input type="email" required name="email" value="{{ old('email') }}"
                                                placeholder="Please enter a Email" class="form-control" />
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Phone No<span style="color:red">*</span></label>
                                            <input type="text" name="phone_no" value="{{ old('phone_no') }}"
                                                placeholder="Please enter a phone number" class="form-control" required
                                                pattern="\d{10}" title="Please enter a 10-digit phone number" />

                                        </div>
                                        <input type="hidden" name="role_id" value="2" />
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Password<span style="color:red">*</span></label>
                                            <input type="Password" required name="password" value="{{ old('password') }}"
                                                placeholder="Please enter a Password" class="form-control" />
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label">Confirm Password<span
                                                    style="color:red">*</span></label>
                                            <input type="password" name="password_confirmation"
                                                value="{{ old('password_confirmation') }}"
                                                placeholder="Please confirm your password" class="form-control" required />
                                        </div>


                                        <!-- Basic Details -->
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="mb-3">Basic Details</h5>
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Name <span style="color:red">*</span></label>
                                                    <input type="text" name="name" class="form-control" required
                                                        placeholder="Enter Name">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Email <span
                                                            style="color:red">*</span></label>
                                                    <input type="email" name="email" class="form-control" required
                                                        placeholder="Enter Email">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Mobile <span
                                                            style="color:red">*</span></label>
                                                    <input type="text" name="mobile" class="form-control"
                                                        pattern="\d{10}" required placeholder="Enter 10 digit Mobile">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Password <span
                                                            style="color:red">*</span></label>
                                                    <input type="password" name="password" class="form-control" required
                                                        placeholder="Enter Password">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">IP Address</label>
                                                    <input type="text" name="ip_address" class="form-control"
                                                        placeholder="Enter IP Address">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Max Transfer Amount <span
                                                            style="color:red">*</span></label>
                                                    <input type="number" name="max_transfer_amount" class="form-control"
                                                        required placeholder="Enter Max Transfer Amount">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">API Provider <span
                                                            style="color:red">*</span></label>
                                                    <select name="api_provider" class="form-select" required>
                                                        <option value="">Select Provider</option>
                                                        <option value="Upay">Upay</option>
                                                        <option value="OTHER">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Max TPS</label>
                                                    <input type="number" name="max_tps" class="form-control"
                                                        placeholder="Enter Max TPS">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payin Commission -->
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="mb-3">Payin Commission</h5>
                                            <div class="row">
                                                <div class="col-lg-2"><input type="number" name="payin_commission1"
                                                        class="form-control" placeholder="Commission 1"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payin_percentage1" class="form-control"
                                                        placeholder="Percentage 1"></div>
                                                <div class="col-lg-2"><input type="number" name="payin_commission2"
                                                        class="form-control" placeholder="Commission 2"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payin_percentage2" class="form-control"
                                                        placeholder="Percentage 2"></div>
                                                <div class="col-lg-2"><input type="number" name="payin_commission3"
                                                        class="form-control" placeholder="Commission 3"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payin_percentage3" class="form-control"
                                                        placeholder="Percentage 3"></div>
                                            </div>
                                        </div>

                                        <!-- Payout Commission -->
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="mb-3">Payout Commission</h5>
                                            <div class="row">
                                                <div class="col-lg-2"><input type="number" name="payout_commission1"
                                                        class="form-control" placeholder="Commission 1"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payout_percentage1" class="form-control"
                                                        placeholder="Percentage 1"></div>
                                                <div class="col-lg-2"><input type="number" name="payout_commission2"
                                                        class="form-control" placeholder="Commission 2"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payout_percentage2" class="form-control"
                                                        placeholder="Percentage 2"></div>
                                                <div class="col-lg-2"><input type="number" name="payout_commission3"
                                                        class="form-control" placeholder="Commission 3"></div>
                                                <div class="col-lg-2"><input type="number" step="0.01"
                                                        name="payout_percentage3" class="form-control"
                                                        placeholder="Percentage 3"></div>
                                            </div>
                                        </div>

                                        <!-- DMT -->
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="mb-3">GST <spa style="color:red">*</spa></h5>
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">

                                                    <input type="text" required name="gst" class="form-control"
                                                        placeholder="Enter GST">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="border rounded p-3 mb-4">
                                            <h5 class="mb-3">Status</h5>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" name="status" value="1"
                                                            class="form-check-input">
                                                        <label class="form-check-label">Active</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" name="api_status" value="1"
                                                            class="form-check-input">
                                                        <label class="form-check-label">API Status</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" name="payout_commission_in_percent"
                                                            value="1" class="form-check-input">
                                                        <label class="form-check-label">Payout Commission in %</label>
                                                    </div>
                                                    {{-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" name="node_bypass" value="1"
                                                            class="form-check-input">
                                                        <label class="form-check-label">Node Bypass</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row row mt-4">
                                        <div class="col-lg-4 mb-4 d-flex justify-content-start">
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                                <i class="feather-arrow-left me-2"></i>
                                                <spann>Back</spann>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 mb-4 offset-lg-4 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-save me-2"></i>
                                                <spann>Save</spann>
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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('customerForm');
                form.addEventListener('submit', function(event) {
                    const phoneInput = document.querySelector('input[name="phone_no"]');
                    const phoneValue = phoneInput.value;

                    if (!/^\d{10}$/.test(phoneValue)) {
                        event.preventDefault(); // Prevent form submission
                        alert('Please enter a valid 10-digit phone number.');
                        phoneInput.focus();
                    }
                });
            });
        </script>
    @endpush
@endsection
