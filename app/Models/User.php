<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use  App\Models\Comission;
use App\Models\UserBank;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded =[];
    protected $table ='users';


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add inside App\Models\User
        public function bank()
        {
            return $this->hasOne(UserBank::class, 'user_id', 'id');
        }

        public function commissions()
        {
            return $this->hasMany(Comission::class, 'user_id', 'id');
        }

        public function payinCommission()
        {
            return $this->hasOne(Comission::class, 'user_id', 'id')->where('type', 'payin');
        }

public function payoutCommission()
{
    return $this->hasOne(Comission::class, 'user_id', 'id')->where('type', 'payout');
}



    public function fetchCustomerData($request, $columns) {
        $query =User::where('id', '!=', '');
        if(isset($request->customer_id)){
            $query->whereIn("id",$request->customer_id);
        }
        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
        if (isset($request['search']['value']) && !empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->where('first_name', 'like', '%' . $searchValue . '%')
                  ->orWhere('last_name', 'like', '%' . $searchValue . '%')
                  ->orWhere('email', 'like', '%' . $searchValue . '%')
                  ->orWhere('phone_no', 'like', '%' . $searchValue . '%');
            });
        }

        if (isset($request->order_column)) {
            $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $customers = $query->orderBy('created_at', 'desc');
        }
        return $customers;
    }
}
