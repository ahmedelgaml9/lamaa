<?php

namespace App\Exports;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;


class ReturnedOrdersExport implements FromCollection ,WithHeadings,WithEvents
{ 

    private $headings = [
        
        '#',
        'اسم العميل',
        'رقم الهاتف',
        'تاريخ التوصيل',
        'الاجمالى',
        'وسيلة الدفع',
        'الحالة'
    ];


    public function registerEvents(): array
    {
        return [

            BeforeSheet::class  =>function(BeforeSheet $event){

                $event->getDelegate()->setRightToLeft(true);
            }
        ];
    }


    public function collection()
    {
        
        $orders= Order::where('status','=','11')->get();
        $payload=[];
        
        foreach($orders as $key => $value) {
    
            $payload[] = array('id'=>++$key ,'name' => $value->user->name ,'phone' => $value->user->mobile,'deliver_date'=>$value->delivery_date->format('Y-m-d'),'total'=>$value->total ,'payment'=>$value->PaymentMethod ,'status'=>show_status($value->status));
        }
         
        return collect($payload);
    }

    public function headings() : array
    {

         return $this->headings;	
    }    


}
        

