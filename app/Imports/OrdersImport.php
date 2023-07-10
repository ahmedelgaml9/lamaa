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

class OrdersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     * @return bool
     */
    public function collection(Collection $rows)
    {
        $ordersArray = [];
        $createdOrders = [];
        foreach ($rows as $row)
        {
            if(!$row['order_created_at']){
                 break;
            }

            if(in_array($row['no'], $createdOrders)){
                continue;
            }
            $createdOrders[] = $row['no'];

            $customer = User::where('mobile', $row['customer_mobile'])->orWhere('email', $row['customer_email'])->first();
            if(!$customer){
                $customer = User::create([
                    'mobile' => $row['customer_mobile'],
                    'user_type' => 'customer',
                    'email' => $row['customer_email'],
                    'name' => $row['customer_name'],
                    'password' => bcrypt(uniqid())
                ]);
            }

            $product = Product::where('sku', $row['product_sku'])->first();
            if(!$product){
                $product = Product::create([
                    'name' => $row['product_name'],
                    'sku' => $row['product_sku'],
                    'available' => 0,
                    'quantity' => $row['product_current_quantity']
                ]);
            }

                $order = Order::create([
                    'user_id' => $customer->id,
                    'total' => $row['order_total'],
                    'created_at' => $row['order_created_at'],
                    'delivery_date' => $row['order_delivery_date'],
                    'delivery_start_time' => $row['order_delivery_start_time'],
                    'delivery_end_time' => $row['order_delivery_end_time']
                ]);

            $ordersArray[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => (int) $row['product_quantity'],
                'unit_price' => (float) $row['product_unit_price'],
                'amount' => (float) $row['product_unit_price'] * (int) $row['product_quantity'],
                'payment_method' => $row['payment_payment_method'],
                'payment_status' => $row['payment_status']
            ];

        }
        foreach ($ordersArray as $productArray){
            OrderProduct::create([
                'order_id' => $productArray['order_id'],
                'product_id' => $productArray['product_id'],
                'quantity' => $productArray['quantity'],
                'amount' => $productArray['amount']
            ]);

            $paymentMethod = PaymentMethod::where('gateway', $productArray['payment_method'])->first();
            Payment::updateOrCreate(['referenceable_id' => $productArray['order_id'], 'referenceable_type' => 'orders'], ['payment_method_id' => $paymentMethod->id, 'referenceable_id' => $productArray['order_id'], 'referenceable_type' => 'orders', 'status' => $productArray['payment_status']]);
        }
        return true;
    }
}
