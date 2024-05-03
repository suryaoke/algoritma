<?php

namespace App\Exports;


use App\Models\Jadwal;
use App\Models\Schedule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class TestExport1 implements FromView, WithDrawings
{
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {

        $data['schedules'] = Schedule::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'schedules.teachs_id', '=', 'teachs.id')
            ->where('schedules.type', $this->id) // Menambahkan kondisi WHERE untuk kolom 'type'
            ->orderBy('teachs.class_room', 'asc')
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        return view(
            'admin.jadwal.excel1',
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
