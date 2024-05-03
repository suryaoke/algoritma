<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchedulesExport1 implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return $this->schedules;
    }

    public function map($schedule): array
    {
        static $lastEndTime = null;
        static $lastDaysId = null;

        $originalValue = isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : 0;
        $calculatedValue = $originalValue * 40;

        // Hitung jam dan menit berdasarkan nilai yang dihitung
        $hours = floor($calculatedValue / 60);
        $minutes = $calculatedValue % 60;

        // Format rentang waktu
        if ($lastEndTime && $schedule->days_id == $lastDaysId) {
            $startTime = Carbon::parse($lastEndTime);
        } else {
            // Ubah waktu mulai berdasarkan nilai name_day
            if (isset($schedule->day->name_day)) {
                if ($schedule->day->name_day == 'Senin' || $schedule->day->name_day == 'Jumat') {
                    $startTime = Carbon::createFromTime(8, 0);
                } else {
                    $startTime = Carbon::createFromTime(7, 0);
                }
            } else {
                $startTime = Carbon::createFromTime(7, 0);
            }
        }
        // Hitung waktu selesai
        $endTime = $startTime
            ->copy()
            ->addHours($hours)
            ->addMinutes($minutes);

        // Check if it's the last schedule for the day
        // Check if it's the last schedule for the day
        $scheduleKey = $this->schedules->search(function ($item) use ($schedule) {
            return $item->id == $schedule->id;
        });

        if ($scheduleKey !== false && ($scheduleKey === $this->schedules->count() - 1 || $schedule->days_id !== $this->schedules[$scheduleKey + 1]->days_id)) {
            // Mengeset waktu selesai menjadi pukul 15:30 hanya untuk course terakhir pada setiap days_id
            $endTime = Carbon::createFromTime(15, 40);
        }


        $formattedTime = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');

        // Simpan waktu selesai untuk digunakan pada iterasi selanjutnya
        $lastEndTime = $endTime->format('H:i');
        $lastDaysId = $schedule->days_id;

        return [
            'Hari' => isset($schedule->day->name_day) ? $schedule->day->name_day : '',
            'Jam' => $formattedTime,
            'Kelas' => isset($schedule->teach->class_room) ? $schedule->teach->class_room : '',
            'Kode Guru' => isset($schedule->teach->guru->code_gurus) ? $schedule->teach->guru->code_gurus : '',
            'Mata Pelajaran' => isset($schedule->teach->course->name) ? $schedule->teach->course->name : '',
            'JP' => isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : '',
            'Ruangan' => isset($schedule->room->name) ? $schedule->room->name : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Hari',
            'Jam',
            'Kelas',
            'Kode Guru',
            'Mata Pelajaran',
            'JP',
            'Ruangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return [
            'A1:G1' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'A2:' . $highestColumn . $highestRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
