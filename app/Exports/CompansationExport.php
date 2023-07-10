<?php

namespace App\Exports;
use App\Models\Compansations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;

class CompansationExport implements FromCollection ,WithHeadings,WithEvents
{ 

    private $headings = [

        '#',
        'نوع التعويض',
        'رقم الطلب',
        'الاجراء',
        'ملاحظات الادارة',
        'اسم العميل',
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
        
        $compansations = Compansations::get();

        $payload=[];
                
        foreach($compansations as $key => $value) {
    
            $payload[] = array('id'=>++$key ,'type' => $value->type ,'order_id' => $value->order_id,'action'=>$value->action ,'staff_notes'=>$value->staff_notes ,'user_id'=>$value->user->name);
        }
         
        return collect($payload);
    }

    public function headings() : array
    {

         return $this->headings;	
    }    


}
        

