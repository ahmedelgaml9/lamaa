<?php

namespace App\Exports;
use App\Models\Offer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;

class OfferExport implements FromCollection ,WithHeadings,WithEvents
{ 
    private $headings = [

        '#',
        'اسم العرض',
         'الطلب',
         'احصل على',
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
        
        $offers= Offer::get();
        
        $payload=[];

        foreach($offers as $key => $value) {
    
            $payload[] = array('id'=>++$key ,'name' => $value->name ,'take' => $value->take ,'get'=>$value->get,'status'=>show_status($value->status));
        }
         
        return collect($payload);
    }

    public function headings() : array
    {
         return $this->headings;	
    }    


}
        

