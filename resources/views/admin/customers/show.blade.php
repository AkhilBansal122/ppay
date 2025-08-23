@extends('admin.layouts.app')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Users</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">View</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="{{ route('users.index') }}" class="page-header-right-close-toggle">
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
                                <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}" />
                                @include('admin.customers.form',compact('user','view'))

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
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('customerForm');
        form.addEventListener('submit', function (event) {
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
