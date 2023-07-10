<?php

namespace App\Exports;
use App\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use DB;


class EmailSmsExport implements FromCollection ,WithHeadings,WithEvents
{ 

    private $headings = [

        '#',
        'اسم العميل',
        'رقم الهاتف',
        'البريد الالكترونى',
        'المدينة',
        'عدد الطلبات',
        'متوسط السلة',
        'تاريخ اخر طلب',
        'حالة اخر طلب',
        'متوسط الفترة الزمنية للطلبات',
        'المنتج الأكثر طلبا',
        'تقييم العميل',
        'تقييم العميل للمندوب',
        'حالة العميل',

    ];


    public function collection()
    {
        
        $customers = User::where('user_type','=','customer')->paginate(100);

        $payload=[];

        foreach($customers as $key => $value) {

        $order = Order::where('user_id',$value->id)->orderBy('created_at','desc')->first();

        $most_products = $this->productordered($value->id);

        $payload[] = array('id'=> ++ $key ,'name' => $value->name ,'phone' => $value->mobile,'email'=>$value->email,'city'=>$value->city_name  ,'orders_numbers'=>$value->orders_count , 'oldcarts'=>'' ,'date'=>!is_null($order) ? $order->created_at:null ,'order_status'=>show_order_status(!is_null($order) ? $order->status:null),'order_time_period'=>$value->order_time,
          
         'product'=>!is_null($most_products)? $most_products->title :null, 'customer_rate'=>'' ,'driver_rate'=>!is_null($order) ? $order->driver_rating_stars:null ,'status'=>show_status($value->status));
        
        }
         
         return collect($payload);
    
    }

    public function registerEvents(): array
    {
       
        return [

            BeforeSheet::class=>function(BeforeSheet $event){

                $event->getDelegate()->setRightToLeft(true);
            }
        ];
    }

    public function headings() : array
    {
         return $this->headings;	
    }  
    
    
    protected function  productordered($user){

        $products = DB::table('products')
        ->leftJoin('order_products', 'products.id', '=', 'order_products.product_id' )
        ->select( DB::raw( 'count(*) as pro_count, product_id' ) )
        ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id' )
        ->where( 'orders.user_id', '=', $user )
        ->orderBy('pro_count', 'DESC' )
        ->groupBy('product_id')
        ->first();

       if(!empty($products))
       {

        $product = Product::where('id', $products->product_id)->first();
        
        return $product;

       }

       else{

         return null;

       }

    }

}
        

