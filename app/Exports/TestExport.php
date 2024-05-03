<?php

namespace App\Exports;


use App\Models\Jadwal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class TestExport implements FromView, WithDrawings
{
    public function view(): View
    {

        $data['schedules'] = Jadwal::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'jadwals.teachs_id', '=', 'teachs.id')
            ->orderBy('teachs.class_room', 'asc') // Urutkan berdasarkan class_room terkecil
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        return view(
            'admin.jadwal.excel',
            $data
        );
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/backend/dist/images/smk1.png'));
        $drawing->setHeight(65);
        $drawing->setCoordinates('B3');

        return $drawing;
    }
   
}
