<?php

namespace App\Exports;
use App\Models\PromoCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;


class PromoCodesExport implements FromCollection ,WithHeadings,WithEvents
{ 

    private $headings = [

        '#',
        'الكود',
        'عدد المستخدمين',
        'عدد مرات الاستخدام',
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

         $campaigns = Campaign::get()->pluck('promocode');

         $promocodes = PromoCode::get();

         $payload=[];

        foreach($promocodes as  $key => $value) {
    
            $payload[] = array('id'=>++$key ,'code' => $value->code ,'number_of_users' => count($value->users), 'num_of_use'=>$value->num_of_use , 'status'=>show_status($value->status));
        }
         
        return collect($payload);
    }

    public function headings() : array
    {
         return $this->headings;	
    }    


}
        

