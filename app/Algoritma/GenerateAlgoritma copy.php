<?php

namespace app\Algoritma;

use App\Models\Day;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teach;
use App\Models\Time;
use App\Models\Timenotavailable;
use DB;
use Illuminate\Support\Facades\Log;

class GenerateAlgoritma
{
    public function randKromosom($kromosom, $count_teachs, $input_year, $input_semester)
    {
        Schedule::truncate();

        $data = [];

        // Ambil semua pengajaran dari tabel Teach yang sesuai dengan semester dan tahun tertentu
        $teachs = Teach::whereHas('course', function ($query) use ($input_semester) {
            $query->where('courses.semester', $input_semester);
        })
            ->where('year', $input_year)
            ->get();

        // Iterasi melalui setiap pengajaran
        foreach ($teachs as $teach) {
            $values = [];
            for ($i = 0; $i < $kromosom; $i++) {
                // Pilih hari, jam, dan ruangan secara acak
                $day   = Day::inRandomOrder()->first();
                $room  = Room::where('jurusan', $teach->course->jurusan)
                    ->where('type', $teach->course->type)
                    ->inRandomOrder()
                    ->first();
                $time  = Time::where('jp', $teach->course->jp)->inRandomOrder()->first();

                // Periksa apakah slot waktu yang dipilih sudah terisi, jika ya, ulangi proses pemilihan
                $existingSchedule = Schedule::where([
                    'days_id' => $day->id,
                    'times_id' => $time->id,
                    'rooms_id' => $room->id
                ])->exists();

                if ($existingSchedule) {
                    // Jika sudah terisi, maka ulangi proses pemilihan
                    $i--;
                    continue;
                }

                $params = [
                    'teachs_id' => $teach->id,
                    'days_id'   => $day->id,
                    'times_id'  => $time->id,
                    'rooms_id'  => $room->id,
                    'type'      => $i + 1,
                ];

                $schedule = Schedule::create($params);

                // Tambahkan $teach ke dalam $values jika diperlukan
                $values[] = $teach;
            }
            $data[] = $values;
        }

        return $data;
    }

   
}
