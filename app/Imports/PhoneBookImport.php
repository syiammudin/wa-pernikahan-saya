<?php

namespace App\Imports;

use App\Models\PhoneBook;
use Maatwebsite\Excel\Concerns\ToModel;

class PhoneBookImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new PhoneBook([
            'user_id' => auth()->user()->id,
            'name'     => $row[0],
            'phone'    => $row[1],
        ]);
    }
}
