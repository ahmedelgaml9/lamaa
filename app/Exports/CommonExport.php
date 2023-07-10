<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;


class CommonExport implements FromCollection ,WithHeadings,WithEvents
{
    public function __construct($data)
    {
        $this->rows = $data['rows'];
        $this->headings = $data['headings'];
    }

    private $headings;
    private $rows;

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->rows);
    }

    public function registerEvents(): array
    {
        return [

            BeforeSheet::class  =>function(BeforeSheet $event){
                $event->getDelegate()->setRightToLeft(true);
            },

            AfterSheet::class => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);

            },
        ];
    }

    public function headings() : array
    {
        return $this->headings;
    }
 
}


