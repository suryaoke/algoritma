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


    public function checkPinalty()
    {

        $schedules = Schedule::select(DB::raw('teachs_id, days_id, rooms_id, type, count(*) as `jumlah`'))
            ->groupBy('teachs_id')
            ->groupBy('days_id')
            ->groupBy('rooms_id')
            ->groupBy('type')
            ->having('jumlah', '>', 1)
            ->get();

        $result_schedules = $this->increaseProccess($schedules);

        $schedules = Schedule::select(DB::raw('times_id, days_id, rooms_id, type, count(*) as `jumlah`'))
            ->groupBy('times_id')
            ->groupBy('days_id')
            ->groupBy('rooms_id')
            ->groupBy('type')
            ->having('jumlah', '>', 1)
            ->get();

        $result_schedules = $this->increaseProccess($schedules);

        $schedules = Schedule::join('teachs', 'teachs.id', '=', 'schedules.teachs_id')
            ->join('gurus', 'gurus.id', '=', 'teachs.gurus_id')
            ->select(DB::raw('gurus_id, days_id, times_id, type, count(*) as `jumlah`'))
            ->groupBy('gurus_id')
            ->groupBy('days_id')
            ->groupBy('times_id')
            ->groupBy('type')
            ->having('jumlah', '>', 1)
            ->get();

        $result_schedules = $this->increaseProccess($schedules);



        if (!empty($schedules)) {
            foreach ($schedules as $key => $schedule) {
                $schedule->value         = $schedule->value + 1;
                $schedule->value_process = $schedule->value_process . "+ 1 ";
                $schedule->save();
            }
        }

        $time_not_availables = Timenotavailable::get();

        if (!empty($time_not_availables)) {
            foreach ($time_not_availables as $k => $time_not_available) {
                $schedules = Schedule::whereHas('teach', function ($query) use ($time_not_available) {
                    $query = $query->whereHas('guru', function ($q) use ($time_not_available) {
                        $q->where('gurus.id', $time_not_available->gurus_id);
                    });
                });

                $schedules = $schedules->where('days_id', $time_not_available->days_id)->where('times_id', $time_not_available->times_id)->get();

                if (!empty($schedules)) {
                    foreach ($schedules as $key => $schedule) {
                        $schedule->value         = $schedule->value + 1;
                        $schedule->value_process = $schedule->value_process . "+ 1 ";
                        $schedule->save();
                    }
                }
            }
        }

        $schedules = Schedule::get();

        foreach ($schedules as $key => $schedule) {
            $schedule->value = 1 / (1 + $schedule->value);
            $schedule->save();
        }

        return $schedules;
    }

    public function increaseProccess($schedules = '')
    {
        if (!empty($schedules)) {
            foreach ($schedules as $key => $schedule) {
                if ($schedule->jumlah > 1) {
                    $schedule_wheres = Schedule::where('type', $schedule->type)->get();
                    foreach ($schedule_wheres as $key => $schedule_where) {
                        $schedule_where->value         = $schedule_where->value + ($schedule->jumlah - 1);
                        $schedule_where->value_process = $schedule_where->value_process . " + " . ($schedule->jumlah - 1);
                        $schedule_where->save();
                    }
                }
            }
        }
        return $schedules;
    }
}
