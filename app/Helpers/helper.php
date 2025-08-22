<?php
use App\Mail\TemplateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

if (!function_exists('universepay_login')) {


    function universepay_login()
    {
$email = config('app.email');
$password = config('app.password');
    $url = "https://universepay.in/api/auth/login?email={$email}&password={$password}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);
       // dd($response);
        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => curl_error($ch)];
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if($result['status']== true){
        // Save token in session
        if (isset($result['access_token'])) {
            Session::put('universepay_token', $result['access_token']);
            Session::save();
            return true;
        }
        else{
            return false;
        }
        }

    }
}


if (!function_exists('universepay_api')) {
    function universepay_api($endpoint, $method = 'GET', $data = [])
    {
        $token = Session::get('universepay_token');
        //print_r($token);
        // Step 1: Auto-login if token is missing
        if (!$token) {
            $loginResponse = universepay_login();
            if (!isset($loginResponse['token'])) {
                return ['error' => 'Login failed: Unable to get token'];
            }
            $token = $loginResponse['token'];
        }

        $baseUrl = "https://universepay.in/api";
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Step 2: Handle HTTP method
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        // Step 3: Headers with token
        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Step 4: Execute
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => curl_error($ch)];
        }

        curl_close($ch);

        $result = json_decode($response, true);
        // Step 5: If token expired, auto-login and retry once
        if (isset($result['message']) && stripos($result['message'], 'unauthorized') !== false) {
            Session::forget('universepay_token');
            $loginResponse = universepay_login();
            if (isset($loginResponse['token'])) {
                return universepay_api($endpoint, $method, $data); // retry
            }
        }

        return $result;
    }
}


function sendMail($email, $template, $data)
{
    try {
        Mail::to($email)->send(new TemplateMail($data));
        info("Mail sent successfully to $email");
    } catch (\Exception $e) {
        info("Failed to send mail: " . $e->getMessage());
    }
}

if (!function_exists('dateFormat')) {

function dateFormat($date){
        return $date->format('d-M-Y h:i A'); }
}
if (!function_exists('actions')) {

    function actions($data) {
        $action = '<div class="hstack gap-2 justify-content-end">';

        if (!empty($data['edit'])) {

            $action .= '<a href="' . $data['edit'].'" class="avatar-text avatar-md">
                <i class="feather feather-eye"></i>
            </a>';
        }
         if (!empty($data['view']) || !empty($data['delete'])) {
            $action .= '<div class="dropdown">
                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                    <i class="feather feather-more-horizontal"></i>
                </a>
                <ul class="dropdown-menu">';

            if (!empty($data['view'])) {
                $viewUrl = $data['view'];
                $action .= '<li>
                    <a class="dropdown-item" href="' . $viewUrl . '">
                        <i class="feather feather-eye me-3"></i>
                        <span>View</span>
                    </a>
                </li>';
            }

            if (isset($data['delete'])) {
                $editUrl = $data['delete'];
                $action .= '<li>
                    <a class="dropdown-item" href="' . $editUrl . '">
                        <i class="feather feather-edit-3 me-3"></i>
                        <span>Edit</span>
                    </a>
                </li>';
            }

            $action .= '<li>
                <a class="dropdown-item printBTN" href="javascript:void(0)">
                    <i class="feather feather-printer me-3"></i>
                    <span>Print</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="feather feather-clock me-3"></i>
                    <span>Remind</span>
                </a>
            </li>
            <li class="dropdown-divider"></li>';

            if (isset($data['archive'])) {
                $action .= '<li>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="feather feather-archive me-3"></i>
                        <span>Archive</span>
                    </a>
                </li>';
            }

            if (isset($data['report_spam'])) {
                $action .= '<li>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="feather feather-alert-octagon me-3"></i>
                        <span>Report Spam</span>
                    </a>
                </li>';
            }

            if (isset($data['delete'])) {
                $action .= '<li class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="feather feather-trash-2 me-3"></i>
                        <span>Delete</span>
                    </a>
                </li>';
            }

            $action .= '</ul>
            </div>';
        }

        $action .= '</div>';
        return $action;
    }
}
if (!function_exists('isActiveInactive')) {
    function isActiveInactive($status, $route, $id) {
        // Determine if the toggle should be checked
        $isChecked = $status == '1' ? 'checked' : '';

        return '
        <div class="form-check form-switch form-switch-sm">
            <input class="form-check-input c-pointer statusChange" type="checkbox" id="formSwitch'.$id.'" '.$isChecked.' data-id="'.$id.'" data-url="'.$route.'">
            <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitch'.$id.'">
                ' . ($status == '1' ? 'Active' : 'Inactive') . '
            </label>
        </div>
        ';
    }
}

/*
        <select class="form-control statusChange" data-id="'.$id.'" data-url="'.$route.'" required name="status" style="display: none;">
            <option value="1" '.($status == '1' ? 'selected' : '').'>Active</option>
            <option value="0" '.($status == '0' ? 'selected' : '').'>Inactive</option>
        </select> */
if (!function_exists('statusChange')) {
 function statusChange($request,$modalName)
{
    // Extract data from request
    $status = $request->input('status');
    $id = $request->input('id');

    // Find the record and update status
    $record = $modalName::find($id); // Replace 'YourModel' with the actual model name

    if ($record) {
        $record->status = $status;
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Record not found.',
        ], 404);
    }
}
}


