<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToCollection, WithHeadingRow
{
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $customer = User::where('mobile', $row['mobile'])->orWhere('email', $row['email'])->first();
            if(!$customer){

                User::create([

                    'mobile' => $row['mobile'],
                    'user_type' => 'customer',
                    'email' => $row['email'],
                    'name' => $row['name'],
                   // 'password' => $row['password']?bcrypt($row['password']):bcrypt(uniqid())
                ]);
            }
        }
        return true;
    }
}
