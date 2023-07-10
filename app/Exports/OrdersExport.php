<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;


class OrdersExport implements FromCollection ,WithHeadings,WithEvents
{
    private $headings = [
        '#',
        'اسم العميل',
        'رقم الهاتف',
        'تاريخ التوصيل',
        'الاجمالى',
        'وسيلة الدفع',
        'سبب الالغاء',
        'الحالة'
    ];


    public function collection()
    {

        $orders= Order::where('status','=','5')->get();
        
        $payload=[];

        foreach($orders as $key => $value) {
    
            $payload[] = array('id'=>++$key ,'name' =>!is_null($value->user) ?$value->user->name: null ,'phone' => !is_null($value->user) ?$value->user->mobile: null,'deliver_date'=>$value->delivery_date,'total'=>$value->total ,'payment'=>$value->PaymentMethod ,'cancel'=>$value->cancelled_reason , 'status'=>show_order_status($value->status));
        }

        return collect($payload);
    }

    public function registerEvents(): array
    {
        return [

            BeforeSheet::class  =>function(BeforeSheet $event){
                $event->getDelegate()->setRightToLeft(true);
            }
        ];
    }

    public function headings() : array
    {
         return $this->headings;
    }

}


