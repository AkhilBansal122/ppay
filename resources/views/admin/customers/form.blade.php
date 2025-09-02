@php
    $readonly = isset($view) && $view ? 'readonly' : '';
    $disabled = isset($view) && $view ? 'disabled' : '';
@endphp

<div class="row border rounded p-3 mb-4">
     <!-- Username -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Username <span style="color:red">*</span></label>
         <input type="text" {{ $readonly }} {{ $disabled }} required name="name" value="{{ old('name', $user->name ?? '') }}"
             placeholder="Please enter a username" class="form-control" />
     </div>

     <!-- First Name -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">First Name <span style="color:red">*</span></label>
         <input type="text" {{ $readonly }} {{ $disabled }} required name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}"
             placeholder="Please enter a first name" class="form-control" />
     </div>

     <!-- Last Name -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Last Name<span style="color:red">*</span></label>
         <input type="text" {{ $readonly }} {{ $disabled }} required name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
             placeholder="Please enter a last name" class="form-control" />
     </div>

     <!-- Email -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Email<span style="color:red">*</span></label>
         <input type="email"  {{ $readonly }} {{ $disabled }} required name="email" value="{{ old('email', $user->email ?? '') }}"
             placeholder="Please enter an Email" class="form-control" />
     </div>

     <!-- Phone No -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Phone No<span style="color:red">*</span></label>
         <input type="text" {{ $readonly }} {{ $disabled }} name="phone_no" value="{{ old('phone_no', $user->phone_no ?? '') }}"
             placeholder="Please enter a phone number" class="form-control" required pattern="\d{10}"
             title="Please enter a 10-digit phone number" />
     </div>

     <!-- Hidden Role -->
     <input type="hidden" name="role_id" value="2" />
     @if(empty($disabled))
     <!-- Password -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Password <span style="color:red">{{ isset($user) ? '' : '*' }}</span></label>
         <input type="password" name="password" class="form-control" placeholder="Please enter a Password"
             {{ isset($user) ? '' : 'required' }}>
     </div>

     <!-- Confirm Password -->
     <div class="col-lg-4 mb-4">
         <label class="form-label">Confirm Password <span style="color:red">{{ isset($user) ? '' : '*' }}</span></label>
         <input type="password" name="password_confirmation" class="form-control"
             placeholder="Please confirm your password" {{ isset($user) ? '' : 'required' }}>
     </div>
@endif
     <!-- Basic Details -->
     {{-- <div class="border rounded p-3 mb-4">
         <h5 class="mb-3">Bank Details</h5>
         <!-- Bank ID -->
         <input type="hidden" name="bank_id" value="{{ $user->bank->id ?? '' }}" />

         <div class="row">
             <div class="col-lg-4 mb-3">
                 <label class="form-label">Name <span style="color:red">*</span></label>
                 <input type="text" {{ $readonly }} {{ $disabled }} name="bank_name" value="{{ old('bank_name', $user->bank->bank_name ?? '') }}"
                     class="form-control" required placeholder="Enter Name">
             </div>

             <div class="col-lg-4 mb-3">
                 <label class="form-label">Email <span style="color:red">*</span></label>
                 <input type="email" {{ $readonly }} {{ $disabled }} name="bank_email" value="{{ old('bank_email', $user->bank->email ?? '') }}"
                     class="form-control" required placeholder="Enter Email">
             </div>

             <div class="col-lg-4 mb-3">
                 <label class="form-label">Mobile <span style="color:red">*</span></label>
                 <input type="text" {{ $readonly }} {{ $disabled }} name="bank_mobile"
                     value="{{ old('bank_mobile', $user->bank->bank_mobile ?? '') }}" class="form-control"
                     pattern="\d{10}" required placeholder="Enter 10 digit Mobile">
             </div>
                @if(empty($disabled))

             <div class="col-lg-4 mb-3">
                 <label class="form-label">Password
                     @if (!isset($user->id))
                         <span style="color:red">*</span>
                     @endif
                 </label>
                 <input type="password" name="bank_password" class="form-control"
                     @if (!isset($user->id)) required @endif placeholder="Enter Password">
             </div>
             @endif
             <div class="col-lg-4 mb-3">
                 <label class="form-label">IP Address</label>
                 <input type="text" {{ $readonly }} {{ $disabled }} name="ip_address"
                     value="{{ old('ip_address', $user->bank->ip_address ?? '') }}" class="form-control"
                     placeholder="Enter IP Address">
             </div>

             <div class="col-lg-4 mb-3">
                 <label class="form-label">Max Transfer Amount <span style="color:red">*</span></label>
                 <input type="number" {{ $readonly }} {{ $disabled }} name="max_transfer_amount"
                     value="{{ old('max_transfer_amount', $user->bank->max_transfer_amount ?? '') }}"
                     class="form-control" required placeholder="Enter Max Transfer Amount">
             </div>

             <div class="col-lg-4 mb-3">
                 <label class="form-label">API Provider <span style="color:red">*</span></label>
                 <select {{ $readonly }} {{ $disabled }} name="api_provider" class="form-select" required>
                     <option value="">Select Provider</option>
                     <option value="Upay"
                         {{ old('api_provider', $user->bank->api_provider ?? '') == 'Upay' ? 'selected' : '' }}>Upay
                     </option>
                     <option value="OTHER"
                         {{ old('api_provider', $user->bank->api_provider ?? '') == 'OTHER' ? 'selected' : '' }}>Other
                     </option>
                 </select>
             </div>

             <div class="col-lg-4 mb-3">
                 <label class="form-label">Max TPS</label>
                 <input {{ $readonly }} {{ $disabled }} type="number" name="max_tps" value="{{ old('max_tps', $user->bank->max_tps ?? '') }}"
                     class="form-control" placeholder="Enter Max TPS">
             </div>
         </div>
     </div> --}}


     <!-- Payin Commission -->
     <!-- Payin Commission -->
     <div class="border rounded p-3 mb-4">
         <h5 class="mb-3">Payin Commission</h5>
         <!-- Payin Commission ID -->
         <input type="hidden" name="payin_commission_id" value="{{ $user->payinCommission->id ?? '' }}" />

         <div class="row">
             @for ($i = 1; $i <= 3; $i++)
                 <div class="col-lg-2 mb-2">
                     <input {{ $readonly }} {{ $disabled }} type="number" name="payin_commission{{ $i }}"
                         value="{{ old('payin_commission' . $i, $user->payinCommission->{'commission' . $i} ?? '') }}"
                         class="form-control" placeholder="Commission {{ $i }}">
                 </div>
                 <div class="col-lg-2 mb-2">
                     <input {{ $readonly }} {{ $disabled }} type="number" step="0.01" name="payin_percentage{{ $i }}"
                         value="{{ old('payin_percentage' . $i, $user->payinCommission->{'percentage' . $i} ?? '') }}"
                         class="form-control" placeholder="Percentage {{ $i }}">
                 </div>
             @endfor
         </div>
     </div>


     <!-- Payout Commission -->
     <div class="border rounded p-3 mb-4">
         <h5 class="mb-3">Payout Commission</h5>
         <!-- Payout Commission ID -->
         <input type="hidden" name="payout_commission_id" value="{{ $user->payoutCommission->id ?? '' }}" />

         <div class="row">
             @for ($i = 1; $i <= 3; $i++)
                 <div class="col-lg-2 mb-2">
                     <input {{ $readonly }} {{ $disabled }} type="number" name="payout_commission{{ $i }}"
                         value="{{ old('payout_commission' . $i, $user->payoutCommission->{'commission' . $i} ?? '') }}"
                         class="form-control" placeholder="Commission {{ $i }}">
                 </div>
                 <div class="col-lg-2 mb-2">
                     <input {{ $readonly }} {{ $disabled }} type="number" step="0.01" name="payout_percentage{{ $i }}"
                         value="{{ old('payout_percentage' . $i, $user->payoutCommission->{'percentage' . $i} ?? '') }}"
                         class="form-control" placeholder="Percentage {{ $i }}">
                 </div>
             @endfor
         </div>
     </div>

     <!-- GST -->
     <div class="border rounded p-3 mb-4">
         <h5 class="mb-3">GST Amount<span style="color:red">*</span></h5>
         <div class="row">
             <div class="col-lg-4 mb-3">
                 <input {{ $readonly }} {{ $disabled }} type="text" name="gst" value="{{ old('gst', $user->gst ?? '') }}"
                     class="form-control" required placeholder="Enter GST Amount">
             </div>
         </div>
     </div>


     <!-- Status -->
     <div class="border rounded p-3 mb-4">
         <h5 class="mb-3">Status</h5>
         <div class="row">
             <div class="col-lg-12">
                 <div class="form-check form-check-inline">
                     <input {{ $readonly }} {{ $disabled }}  type="checkbox" name="status" value="1" class="form-check-input"
                         {{ old('status', $user->status ?? false) ? 'checked' : '' }}>
                     <label class="form-check-label">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                     <input {{ $readonly }} {{ $disabled }} type="checkbox" name="api_status" value="1" class="form-check-input"
                         {{ old('api_status', $user->api_status ?? false) ? 'checked' : '' }}>
                     <label class="form-check-label">API Status</label>
                 </div>
                 <div class="form-check form-check-inline">
                     <input {{ $readonly }} {{ $disabled }} type="checkbox" name="payout_commission_in_percent" value="1"
                         class="form-check-input"
                         {{ old('payout_commission_in_percent', $user->payout_commission_in_percent ?? false) ? 'checked' : '' }}>
                     <label class="form-check-label">Payout Commission in %</label>
                 </div>
             </div>
         </div>
     </div>



 </div>
