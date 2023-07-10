<?php

namespace App\Imports;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $insert =[];

        foreach ($rows as $row)
        {
            $insert[] =[
                
                'id' => $row[0],
                'mobile' =>$row[1],
            ];
        }
        
       return $insert;
    }
}
