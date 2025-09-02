<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use  App\Models\User;
use  App\Models\Comission;
use App\Models\UserBank;
use Illuminate\Support\Arr;
use DB;
use Auth;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct(){
        $this->middleware('permission:Users-Management', ['only' => ['index','store','create','edit','destroy','update']]);
        $this->Model = new User;
        $this->customer_id = DB::table('model_has_roles')->where("role_id",2)->pluck('model_id')->toArray();
        $this->columns = [
            "id",
            'first_name',
            'last_name',
            'email',
            'phone_no',
            'status',
            'created_at'
        ];

    }

    public function index(Request $request)
    {
        return view('admin.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where("id","<>",1)->get();
        return view('admin.customers.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            DB::beginTransaction(); // Start the transaction

        try {
            $adminId = Auth::id();

            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
              //  'bank_email' => 'required|email|max:255',
              //  'bank_mobile' =>'required|numeric|digits:10',
                'phone_no' => 'required|numeric|digits:10', // Ensures only numeric characters are allowed
                'password' => 'required|string|min:8|confirmed', // 'confirmed' handles password confirmation
                'password.confirmed' => 'The password confirmation does not match.',
               // 'bank_password'=> 'required|string|min:8', // 'confirmed' handles password confirmation
                'role_id' => 'required',
               // 'ip_address' => 'required',
               // 'max_transfer_amount' => 'required|numeric',
              //  'api_provider' => 'required|string',
               // 'max_tps' => 'nullable|numeric',
                'payin_commission1' => 'nullable|numeric',
                'payin_percentage1' => 'nullable|numeric',
                'payin_commission2' => 'nullable|numeric',
                'payin_percentage2' => 'nullable|numeric',
                'payin_commission3' => 'nullable|numeric',
                'payin_percentage3' => 'nullable|numeric',
                'payout_commission1' => 'nullable|numeric',
                'payout_percentage1' => 'nullable|numeric',
                'payout_commission2' => 'nullable|numeric',
                'payout_percentage2' => 'nullable|numeric',
                'payout_commission3' => 'nullable|numeric',
                'payout_percentage3' => 'nullable|numeric',
                'gst' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
                'api_status' => 'nullable|boolean',
                'payout_commission_in_percent' => 'nullable|boolean',
              //  'bank_name' => 'required|string|max:255',
               // 'account_number' => 'required|string|unique:users,account_number',
             //   'ifsc_code' => 'required|string|max:20',
                'branch_name' => 'nullable|string|max:255',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['status'] = $request->status ? 1 : 0;
                        $validated['gst'] = $request->gst ?? 0;

            $validated['api_status'] = $request->api_status ? 1 : 0;
            $validated['payout_commission_in_percent'] = $request->payout_commission_in_percent ? 1 : 0;
            $validated['node_bypass'] = $request->has('node_bypass') ? 1 : 0;

                // Create a new user
                $user = User::create([
                    'name'       => $request->name,
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'phone_no'   => $request->phone_no,
                    'gst'=>$request->gst,
                    'status'=>$request->status ? 1:0,
                    'api_status'=>$request->api_status ? 1:0,
                    'payout_commission_in_percent'=>$request->payout_commission_in_percent ? 1:0,

                    'password'   => Hash::make($validated['password']),
                ]);

            $userId = $user->id;


           $roles = Role::where("id",$request->role_id)->first();

           // Assign roles
           $user->assignRole($roles->name);

//             updateOrCreateModel(\App\Models\UserBank::class,
//     ['user_id' => $userId],
//     [
//         'admin_id'       => $adminId,
//         'bank_name'      => $request->bank_name,
//         // 'account_number' => $request->account_number,
//        'ip_address'      => $request->ip_address,
//        'api_provider'    => $request->api_provider,
//        'max_transfer_amount' =>$request->max_transfer_amount,
//        'max_tps'=>$request->max_tps,
//         'email'     =>$request->bank_email,
//         'bank_mobile'=>$request->bank_mobile,
//         'password'       => Hash::make($validated['bank_password']),
//     ]
// );

updateOrCreateModel(\App\Models\Comission::class,
    ['user_id' => $userId, 'type' => 'payin'],
    [
        'admin_id'    => $adminId,
        'commission1' => $request->payin_commission1,
        'percentage1' => $request->payin_percentage1,
        'commission2' => $request->payin_commission2,
        'percentage2' => $request->payin_percentage2,
        'commission3' => $request->payin_commission3,
        'percentage3' => $request->payin_percentage3,
    ]
);

updateOrCreateModel(\App\Models\Comission::class,
    ['user_id' => $userId, 'type' => 'payout'],
    [
        'admin_id'    => $adminId,
        'commission1' => $request->payout_commission1,
        'percentage1' => $request->payout_percentage1,
        'commission2' => $request->payout_commission2,
        'percentage2' => $request->payout_percentage2,
        'commission3' => $request->payout_commission3,
        'percentage3' => $request->payout_percentage3,
    ]
);
        DB::commit(); // Everything went well — commit the transaction


            // Redirect back with success message
            return redirect()->back()->with('success', 'Customer created successfully!');

        } catch (\Exception $e) {
            // Redirect back with a generic error message
                   DB::rollBack(); // Something failed — rollback everything
            dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' =>$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $user = User::with(['bank','payinCommission','payoutCommission'])->findOrFail($id);

        $roles = Role::where("id","<>",1)->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $user->role_id =$user->roles->pluck('id')[0];
        $view = true;
        return view('admin.customers.show',compact('user','roles','userRole','view'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
            $user = User::with(['bank','payinCommission','payoutCommission'])->findOrFail($id);

        $roles = Role::where("id","<>",1)->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $user->role_id =$user->roles->pluck('id')[0];
        return view('admin.customers.edit',compact('user','roles','userRole'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $adminId = Auth::id();
            $userId = $id;
            $user = User::with(['bank','payinCommission','payoutCommission'])->findOrFail($id);
            DB::beginTransaction(); // Start the transaction
        try {
        $input = $request->all();

            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$id,
                'phone_no' => 'required|numeric|digits:10', // Ensures only numeric characters are allowed
                'role_id' => 'required',
             //     'bank_email' => 'required|email|max:255',
               // 'bank_mobile' =>'required|numeric|digits:10',
                'phone_no' => 'required|numeric|digits:10', // Ensures only numeric characters are allowed
                'role_id' => 'required',
               // 'ip_address' => 'required',
               // 'max_transfer_amount' => 'required|numeric',
               // 'api_provider' => 'required|string',
               // 'max_tps' => 'nullable|numeric',
                'payin_commission1' => 'nullable|numeric',
                'payin_percentage1' => 'nullable|numeric',
                'payin_commission2' => 'nullable|numeric',
                'payin_percentage2' => 'nullable|numeric',
                'payin_commission3' => 'nullable|numeric',
                'payin_percentage3' => 'nullable|numeric',
                'payout_commission1' => 'nullable|numeric',
                'payout_percentage1' => 'nullable|numeric',
                'payout_commission2' => 'nullable|numeric',
                'payout_percentage2' => 'nullable|numeric',
                'payout_commission3' => 'nullable|numeric',
                'payout_percentage3' => 'nullable|numeric',
                'gst' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
                'api_status' => 'nullable|boolean',
                'payout_commission_in_percent' => 'nullable|boolean',
               // 'bank_name' => 'required|string|max:255',
               // 'account_number' => 'required|string|unique:users,account_number',
             //   'ifsc_code' => 'required|string|max:20',
               // 'branch_name' => 'nullable|string|max:255',

            ]);

                if ($request->filled('password')) {
                    $rules['password'] = 'required|string|min:8|confirmed';
                }
                if ($request->filled('bank_password')) {
                    $rules['bank_password'] = 'required|string|min:8';
                }
            // Hash the password
                if (!empty(trim($input['password'] ?? ''))) {
                    $user->password = Hash::make($input['password']);
                }
                            // Assume $user is already fetched (e.g., $user = User::find($id);)

                $user->name        = $request->name;
                $user->first_name  = $request->first_name;
                $user->last_name   = $request->last_name;
                $user->email       = $request->email;
                $user->phone_no    = $request->phone_no;
                $user->gst         = $request->gst;

                $user->status                      = $request->has('status') ? 1 : 0;
                $user->api_status                 = $request->has('api_status') ? 1 : 0;
                $user->payout_commission_in_percent = $request->has('payout_commission_in_percent') ? 1 : 0;

                $user->save();

            //     $data = [
            //         'admin_id'            => $adminId,
            //         'bank_name'           => $request->bank_name,
            //         'ip_address'          => $request->ip_address,
            //         'api_provider'        => $request->api_provider,
            //         'max_transfer_amount' => $request->max_transfer_amount,
            //         'max_tps'             => $request->max_tps,
            //         'email'               => $request->bank_email,
            //         'bank_mobile'         => $request->bank_mobile,
            //     ];

            //     if (!empty($request->bank_password)) {
            //         $data['password'] = Hash::make($request->bank_password);
            //     }

            //    // dd($data);
            //     if ($user->bank) {
            //         $user->bank()->update($data);
            //     } else {
            //         $user->bank()->create($data); // associate new bank record
            //     }




updateOrCreateModel(\App\Models\Comission::class,
                ['user_id' => $userId, 'type' => 'payin','id'=>$request->payin_commission_id],
                [
                    'admin_id'    => $adminId,
                    'commission1' => $request->payin_commission1,
                    'percentage1' => $request->payin_percentage1,
                    'commission2' => $request->payin_commission2,
                    'percentage2' => $request->payin_percentage2,
                    'commission3' => $request->payin_commission3,
                    'percentage3' => $request->payin_percentage3,
                ]

);


updateOrCreateModel(\App\Models\Comission::class,
    ['user_id' => $userId, 'type' => 'payout','id'=>$request->payout_commission_id],
    [
        'admin_id'    => $adminId,
        'commission1' => $request->payout_commission1,
        'percentage1' => $request->payout_percentage1,
        'commission2' => $request->payout_commission2,
        'percentage2' => $request->payout_percentage2,
        'commission3' => $request->payout_commission3,
        'percentage3' => $request->payout_percentage3,
    ]
);
        DB::commit(); // Everything went well — commit the transaction

               //   dd($input);

            return redirect()->back()->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
                              DB::rollBack(); // Something failed — rollback everything

            // Redirect back with a generic error message
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getData(Request $request)
    {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        $request->customer_id = $this->customer_id;

        $records = $this->Model->fetchCustomerData($request, $this->columns);
        $total = $records->get();
        if (isset($request->start)) {
            $banners = $records->offset($request->start)->limit($request->length)->get();
        } else {
            $banners = $records->offset($request->start)->limit(count($total))->get();
        }
        $result = [];


        $i = 1;
        foreach ($banners as $value) {
            $data = [];

            $data['srno'] = $i++;
            $data['id'] = $value->id;
            $data['first_name'] = ucfirst($value->first_name);
            $data['last_name'] = ucfirst($value->last_name);
            $data['email'] = ucfirst($value->email);
            $data['phone_no'] = ucfirst($value->phone_no);
            $data['status']=isActiveInactive($value->status,route('users.statusChange'),$value->id);
            $data['created_at'] = dateFormat($value->created_at); // Assuming created_at is a Carbon instance
            $action = actions([
                'edit' => route('users.edit', $value->id),
                'view' => route('users.show', $value->id),   // You may consider removing this if it's not used
                'delete' => ''  // You may consider removing this if it's not used
            ]);

            $data['actions'] = $action;
            $result[] = $data;
        }

        $data = json_encode([
            'data' => $result,
            'recordsTotal' => count($total),
            'recordsFiltered' => count($total),
        ]);
        return $data;
    }

    public function statusChange(Request $request)
    {
       return statusChange($request,$this->Model);
    }
}
