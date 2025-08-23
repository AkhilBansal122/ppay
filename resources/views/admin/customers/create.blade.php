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
                                     @include('admin.customers.form')

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
