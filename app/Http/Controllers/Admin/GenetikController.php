<?php

namespace App\Http\Controllers\Admin;

use App\Algoritma\GenerateAlgoritma;
use App\Exports\SchedulesExport;
use App\Exports\TestExport1;
use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Teach;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GenetikController extends Controller
{
    public function index(Request $request)
    {



        $years = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        return view('admin.genetik.index', compact('years'));
    }

    public function submit(Request $request)
    {
        // Mendapatkan tahun-tahun yang tersedia dari data Teach
        $years = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        // Mendapatkan input dari request
        $input_year = $request->input('year');
        $input_semester = $request->input('semester');
        // Truncate Schedule table
        Schedule::truncate();

        // Ambil semua pengajaran dari tabel Teach yang sesuai dengan semester dan tahun tertentu
        $teachs = Teach::whereHas('course', function ($query) use ($input_semester) {
            $query->where('courses.semester', $input_semester);
        })
            ->where('year', $input_year)
            ->get();

        // Inisialisasi pesan notifikasi


        // Iterasi melalui setiap pengajaran
        foreach ($teachs as $teach) {
            for ($i = 0; $i < 1; $i++) {
                // Pilih hari, jam, dan ruangan secara acak
                $day = Day::inRandomOrder()->first();
                $room = Room::where('jurusan', $teach->course->jurusan)
                    ->where('type', $teach->course->type)
                    ->inRandomOrder()
                    ->first();
                $time = Time::where('jp', $teach->course->jp)->inRandomOrder()->first();

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

                // Tambahkan jadwal baru ke dalam tabel Schedule
                $params = [
                    'teachs_id' => $teach->id,
                    'days_id'   => $day->id,
                    'times_id'  => $time->id,
                    'rooms_id'  => $room->id,
                    'type'      => $i + 1,
                ];

                Schedule::create($params);

                // Update pesan notifikasi

            }
        }

        $counts = Schedule::join('teachs', 'schedules.teachs_id', '=', 'teachs.id')
            ->distinct('schedules.days_id', 'teachs.class_room')
            ->get();

        session()->flash('notification', $counts);
        

        // $counts = Schedule::distinct('days_id')
        // ->selectRaw('days_id, COUNT(teachs_id) as teach_count')
        // ->get();
        // foreach ($counts as $count) {
        //     if (
        //         $count->teach_count == 3
        //     ) {
        //         session()->flash('notification', 'jadwal');
        //     } else {
        //         session()->flash('notification', 'tidak ada');
        //     }
        // }

        return redirect()->route('admin.generates.result', 1);
    } // end method

    public function result(Request $request, $id)
    {

        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchGurus = $request->input('searchgurus');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Schedule::query();

        // Filter berdasarkan nama hari jika searchgurus tidak kosong
        if (!empty($searchDays)) {
            $query->whereHas('day', function ($guruQuery) use ($searchDays) {
                $guruQuery->where('name_day', 'LIKE', '%' . $searchDays . '%');
            });
        }

        // Filter berdasarkan nama guru jika searchgurus tidak kosong
        if (!empty($searchGurus)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchGurus) {
                $teachQuery->whereHas('guru', function ($courseQuery) use ($searchGurus) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchGurus . '%');
                });
            });
        }

        // Filter berdasarkan nama mata Pelajaran jika searchcourse tidak kosong
        if (!empty($searchCourse)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchCourse) {
                $teachQuery->whereHas('course', function ($courseQuery) use ($searchCourse) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchCourse . '%');
                });
            });
        }

        // Filter berdasarkan nama kelas jika searchclass tidak kosong
        if (!empty($searchClass)) {
            $query->whereHas('teach', function ($guruQuery) use ($searchClass) {
                $guruQuery->where('class_room', 'LIKE', '%' . $searchClass . '%');
            });
        }
        // End Bagian search Data //


        // Menampilkan Data  Generate Jadwal//
        $years          = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        $kromosom       = Schedule::select('type')->groupBy('type')->get()->count();
        $crossover      = Setting::where('key', Setting::CROSSOVER)->first();
        $mutasi         = Setting::where('key', Setting::MUTASI)->first();
        $value_schedule = Schedule::where('type', $id)->first();

        $schedules = $query->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->where('schedules.type', $id)->get();
        if (empty($value_schedule)) {
            abort(404);
        }

        for ($i = 1; $i <= $kromosom; $i++) {
            $value_schedules = Schedule::where('type', $i)->first();
            $data_kromosom[] = [
                'value' => $value_schedules->value,

            ];
        }
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();

        return view('admin.genetik.result', compact('day', 'time', 'room', 'schedules', 'years', 'data_kromosom', 'id', 'value_schedule', 'crossover', 'mutasi'));
    }


    public function excel($id)
    {
        $schedules = Schedule::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'schedules.teachs_id', '=', 'teachs.id')
            ->orderBy('teachs.class_room', 'asc') // Urutkan berdasarkan class_room terkecil
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->where('type', $id)
            ->get();

        $export = new SchedulesExport($schedules);
        return Excel::download($export, 'JadwalAlgoritmaSementara.xlsx');
    } // end method

    public function tesExport1($id)
    {
        return Excel::download(new TestExport1($id), 'algoritma.xlsx');
    }

    public function updatejadwal(Request $request, $id)
    {

        $this->validate($request, [
            'days_id' => 'required',
            'times_id' => 'required',
            'rooms_id' => 'required',
            // Tambahkan validasi lain sesuai kebutuhan Anda
        ]);

        // Mengambil data jadwal berdasarkan $id
        $schedule = Schedule::find($id);

        // Mengupdate nilai kolom-kolom jadwal berdasarkan data yang diterima dari formulir
        $schedule->days_id = $request->input('days_id');
        $schedule->times_id = $request->input('times_id');
        $schedule->rooms_id = $request->input('rooms_id');
        // Tambahkan pembaruan lain sesuai kebutuhan Anda

        // Menyimpan perubahan ke dalam database
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Sementara Update SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Jadwal Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    // public function saveDataToMapel($id)
    // {
    //     // Ambil data schedule berdasarkan tipe (type) yang diberikan dalam parameter
    //     Jadwal::truncate();

    //     $schedules = Schedule::where('type', $id)->get();
    //     $jadwalData = [];

    //     // Inisialisasi variabel untuk menyimpan jumlah jp untuk setiap days_id
    //     $jpPerDay = [];

    //     // Perulangan untuk menyimpan data ke tabel Mapel
    //     foreach ($schedules as $schedule) {
    //         // Jika days_id belum ada dalam $jpPerDay untuk class_room tertentu, inisialisasi jumlah jp menjadi 0
    //         if (!isset($jpPerDay[$schedule->teach->class_room][$schedule->days_id])) {
    //             $jpPerDay[$schedule->teach->class_room][$schedule->days_id] = 0;
    //         }


    //         // Jika penambahan jp tidak membuat jumlah melebihi 10, tambahkan ke data yang akan disimpan
    //         if ($jpPerDay[$schedule->teach->class_room][$schedule->days_id] + $schedule->teach->course->jp <= 10) {
    //             $jpPerDay[$schedule->teach->class_room][$schedule->days_id] += $schedule->teach->course->jp;

    //             // Tambahkan data ke array $jadwalData
    //             $jadwalData[] = [
    //                 'teachs_id' => $schedule->teachs_id,
    //                 'rooms_id' => $schedule->rooms_id,
    //                 'times_id' => $schedule->times_id,
    //                 'days_id' => $schedule->days_id,
    //                 'status' => '0',
    //             ];
    //         } else {
    //             // Cari days_id yang memiliki jumlah JP kurang dari 10
    //             $availableDaysId = array_keys($jpPerDay[$schedule->teach->class_room], min($jpPerDay[$schedule->teach->class_room]));

    //             // Pilih days_id yang memiliki jumlah JP kurang dari 10
    //             $selectedDaysId = reset($availableDaysId);

    //             // Ubah days_id
    //             $schedule->days_id = $selectedDaysId;

    //             // Tambahkan data ke array $jadwalData
    //             $jadwalData[] = [
    //                 'teachs_id' => $schedule->teachs_id,
    //                 'rooms_id' => $schedule->rooms_id,
    //                 'times_id' => $schedule->times_id,
    //                 'days_id' => $selectedDaysId,
    //                 'status' => '0',
    //             ];

    //             // Update jumlah JP
    //             $jpPerDay[$schedule->teach->class_room][$selectedDaysId] += $schedule->teach->course->jp;
    //         }
    //     }


    //     // Simpan data ke dalam tabel Jadwal
    //     Jadwal::insert($jadwalData);


    //     $notification = array(
    //         'message' => 'Jadwal Disimpan SuccessFully',
    //         'alert-type' => 'success'
    //     );
    //     // Redirect atau kirim pesan sukses jika diperlukan
    //     return redirect()->back()->with($notification);
    // }

    // public function saveDataToMapel($id)
    // {
    //     // Ambil data schedule berdasarkan tipe (type) yang diberikan dalam parameter
    //     Jadwal::truncate();

    //     $schedules = Schedule::where('type', $id)->get();

    //     // Perulangan untuk menyimpan data ke tabel Mapel
    //     foreach ($schedules as $schedule) {
    //         // Buat instance baru dari model Mapel
    //         $mapel = new Jadwal();

    //         // Set atribut-atribut yang sesuai
    //         $mapel->teachs_id = $schedule->teachs_id;
    //         $mapel->days_id = $schedule->days_id;
    //         $mapel->times_id = $schedule->times_id;
    //         $mapel->rooms_id = $schedule->rooms_id;
    //         $mapel->status = '0';
    //         // Simpan data ke dalam tabel Mapel
    //         $mapel->save();
    //     }

    //     $notification = array(
    //         'message' => 'Jadwal Disimpan SuccessFully',
    //         'alert-type' => 'success'
    //     );
    //     // Redirect atau kirim pesan sukses jika diperlukan
    //     return redirect()->back()->with($notification);
    // }


    public function saveDataToMapel($id)
    {
        // Daftar data yang ingin diupdate
        $schedule = Schedule::where('type', $id)->where('value', 1.00)->first();

        if ($schedule) {
            $dataToUpdate =
                [
                    ['teachs_id' => 226, 'days_id' => 13, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 227, 'days_id' => 14, 'times_id' => 133, 'rooms_id' => 91],
                    ['teachs_id' => 228, 'days_id' => 12, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 229, 'days_id' => 15, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 230, 'days_id' => 14, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 231, 'days_id' => 11, 'times_id' => 125, 'rooms_id' => 90],
                    ['teachs_id' => 232, 'days_id' => 13, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 279, 'days_id' => 15, 'times_id' => 138, 'rooms_id' => 90],
                    ['teachs_id' => 234, 'days_id' => 13, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 235, 'days_id' => 12, 'times_id' => 112, 'rooms_id' => 90],
                    ['teachs_id' => 236, 'days_id' => 11, 'times_id' => 135, 'rooms_id' => 94],
                    ['teachs_id' => 237, 'days_id' => 14, 'times_id' => 106, 'rooms_id' => 91],
                    ['teachs_id' => 238, 'days_id' => 14, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 239, 'days_id' => 12, 'times_id' => 138, 'rooms_id' => 93],
                    ['teachs_id' => 240, 'days_id' => 12, 'times_id' => 108, 'rooms_id' => 89],
                    ['teachs_id' => 241, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 242, 'days_id' => 12, 'times_id' => 106, 'rooms_id' => 90],
                    ['teachs_id' => 243, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 92],
                    ['teachs_id' => 244, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 92],
                    ['teachs_id' => 245, 'days_id' => 13, 'times_id' => 105, 'rooms_id' => 92],
                    ['teachs_id' => 246, 'days_id' => 13, 'times_id' => 108, 'rooms_id' => 90],
                    ['teachs_id' => 247, 'days_id' => 14, 'times_id' => 139, 'rooms_id' => 89],
                    ['teachs_id' => 248, 'days_id' => 11, 'times_id' => 140, 'rooms_id' => 92],
                    ['teachs_id' => 249, 'days_id' => 15, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 250, 'days_id' => 15, 'times_id' => 128, 'rooms_id' => 92],
                    ['teachs_id' => 251, 'days_id' => 11, 'times_id' => 111, 'rooms_id' => 92],
                    ['teachs_id' => 252, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 253, 'days_id' => 12, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 254, 'days_id' => 12, 'times_id' => 136, 'rooms_id' => 94],
                    ['teachs_id' => 255, 'days_id' => 13, 'times_id' => 127, 'rooms_id' => 89],
                    ['teachs_id' => 256, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 93],
                    ['teachs_id' => 257, 'days_id' => 14, 'times_id' => 134, 'rooms_id' => 89],
                    ['teachs_id' => 258, 'days_id' => 11, 'times_id' => 121, 'rooms_id' => 92],
                    ['teachs_id' => 259, 'days_id' => 15, 'times_id' => 129, 'rooms_id' => 90],
                    ['teachs_id' => 260, 'days_id' => 15, 'times_id' => 125, 'rooms_id' => 94],
                    ['teachs_id' => 261, 'days_id' => 14, 'times_id' => 103, 'rooms_id' => 89],
                    ['teachs_id' => 262, 'days_id' => 12, 'times_id' => 101, 'rooms_id' => 90],
                    ['teachs_id' => 263, 'days_id' => 14, 'times_id' => 113, 'rooms_id' => 89],
                    ['teachs_id' => 264, 'days_id' => 14, 'times_id' => 129, 'rooms_id' => 89],
                    ['teachs_id' => 266, 'days_id' => 12, 'times_id' => 109, 'rooms_id' => 91],
                    ['teachs_id' => 267, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 271, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 89],
                    ['teachs_id' => 269, 'days_id' => 14, 'times_id' => 101, 'rooms_id' => 92],
                    ['teachs_id' => 268, 'days_id' => 13, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 272, 'days_id' => 15, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 273, 'days_id' => 15, 'times_id' => 101, 'rooms_id' => 91],
                    ['teachs_id' => 274, 'days_id' => 12, 'times_id' => 125, 'rooms_id' => 93],
                    ['teachs_id' => 275, 'days_id' => 11, 'times_id' => 133, 'rooms_id' => 89],
                    ['teachs_id' => 276, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 89],
                    ['teachs_id' => 277, 'days_id' => 11, 'times_id' => 122, 'rooms_id' => 90],
                    ['teachs_id' => 278, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 233, 'days_id' => 15, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 280, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 281, 'days_id' => 11, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 282, 'days_id' => 14, 'times_id' => 114, 'rooms_id' => 90],
                    ['teachs_id' => 283, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 90],
                ];

            $dataToUpdate1 =
                [
                    ['teachs_id' => 226, 'days_id' => 11, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 227, 'days_id' => 11, 'times_id' => 133, 'rooms_id' => 91],
                    ['teachs_id' => 228, 'days_id' => 12, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 229, 'days_id' => 15, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 230, 'days_id' => 14, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 231, 'days_id' => 13, 'times_id' => 125, 'rooms_id' => 90],
                    ['teachs_id' => 232, 'days_id' => 13, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 279, 'days_id' => 15, 'times_id' => 138, 'rooms_id' => 90],
                    ['teachs_id' => 234, 'days_id' => 13, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 235, 'days_id' => 12, 'times_id' => 112, 'rooms_id' => 90],
                    ['teachs_id' => 236, 'days_id' => 14, 'times_id' => 135, 'rooms_id' => 94],
                    ['teachs_id' => 237, 'days_id' => 14, 'times_id' => 106, 'rooms_id' => 91],
                    ['teachs_id' => 238, 'days_id' => 14, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 239, 'days_id' => 12, 'times_id' => 138, 'rooms_id' => 93],
                    ['teachs_id' => 240, 'days_id' => 12, 'times_id' => 108, 'rooms_id' => 89],
                    ['teachs_id' => 241, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 242, 'days_id' => 12, 'times_id' => 106, 'rooms_id' => 90],
                    ['teachs_id' => 243, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 92],
                    ['teachs_id' => 244, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 92],
                    ['teachs_id' => 245, 'days_id' => 13, 'times_id' => 105, 'rooms_id' => 92],
                    ['teachs_id' => 246, 'days_id' => 13, 'times_id' => 108, 'rooms_id' => 90],
                    ['teachs_id' => 247, 'days_id' => 14, 'times_id' => 139, 'rooms_id' => 89],
                    ['teachs_id' => 248, 'days_id' => 11, 'times_id' => 140, 'rooms_id' => 92],
                    ['teachs_id' => 249, 'days_id' => 15, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 250, 'days_id' => 15, 'times_id' => 128, 'rooms_id' => 92],
                    ['teachs_id' => 251, 'days_id' => 12, 'times_id' => 111, 'rooms_id' => 92],
                    ['teachs_id' => 252, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 253, 'days_id' => 12, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 254, 'days_id' => 11, 'times_id' => 136, 'rooms_id' => 94],
                    ['teachs_id' => 255, 'days_id' => 13, 'times_id' => 127, 'rooms_id' => 89],
                    ['teachs_id' => 256, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 93],
                    ['teachs_id' => 257, 'days_id' => 14, 'times_id' => 134, 'rooms_id' => 89],
                    ['teachs_id' => 258, 'days_id' => 12, 'times_id' => 121, 'rooms_id' => 92],
                    ['teachs_id' => 259, 'days_id' => 15, 'times_id' => 129, 'rooms_id' => 90],
                    ['teachs_id' => 260, 'days_id' => 15, 'times_id' => 125, 'rooms_id' => 94],
                    ['teachs_id' => 261, 'days_id' => 14, 'times_id' => 103, 'rooms_id' => 89],
                    ['teachs_id' => 262, 'days_id' => 12, 'times_id' => 101, 'rooms_id' => 90],
                    ['teachs_id' => 263, 'days_id' => 14, 'times_id' => 113, 'rooms_id' => 89],
                    ['teachs_id' => 264, 'days_id' => 14, 'times_id' => 129, 'rooms_id' => 89],
                    ['teachs_id' => 266, 'days_id' => 12, 'times_id' => 109, 'rooms_id' => 91],
                    ['teachs_id' => 267, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 271, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 89],
                    ['teachs_id' => 269, 'days_id' => 14, 'times_id' => 101, 'rooms_id' => 92],
                    ['teachs_id' => 268, 'days_id' => 13, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 272, 'days_id' => 15, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 273, 'days_id' => 15, 'times_id' => 101, 'rooms_id' => 91],
                    ['teachs_id' => 274, 'days_id' => 12, 'times_id' => 125, 'rooms_id' => 93],
                    ['teachs_id' => 277, 'days_id' => 11, 'times_id' => 133, 'rooms_id' => 89],
                    ['teachs_id' => 276, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 89],
                    ['teachs_id' => 275, 'days_id' => 11, 'times_id' => 122, 'rooms_id' => 90],
                    ['teachs_id' => 278, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 233, 'days_id' => 15, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 280, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 281, 'days_id' => 11, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 282, 'days_id' => 14, 'times_id' => 114, 'rooms_id' => 90],
                    ['teachs_id' => 283, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 90],
                ];
            $dataToUpdate2 =
                [
                    ['teachs_id' => 226, 'days_id' => 13, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 227, 'days_id' => 14, 'times_id' => 133, 'rooms_id' => 91],
                    ['teachs_id' => 228, 'days_id' => 12, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 229, 'days_id' => 15, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 230, 'days_id' => 14, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 231, 'days_id' => 11, 'times_id' => 125, 'rooms_id' => 90],
                    ['teachs_id' => 232, 'days_id' => 13, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 279, 'days_id' => 11, 'times_id' => 138, 'rooms_id' => 90],
                    ['teachs_id' => 234, 'days_id' => 13, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 235, 'days_id' => 12, 'times_id' => 112, 'rooms_id' => 90],
                    ['teachs_id' => 236, 'days_id' => 15, 'times_id' => 135, 'rooms_id' => 94],
                    ['teachs_id' => 237, 'days_id' => 14, 'times_id' => 106, 'rooms_id' => 91],
                    ['teachs_id' => 238, 'days_id' => 14, 'times_id' => 133, 'rooms_id' => 93],
                    ['teachs_id' => 239, 'days_id' => 11, 'times_id' => 138, 'rooms_id' => 93],
                    ['teachs_id' => 240, 'days_id' => 12, 'times_id' => 108, 'rooms_id' => 89],
                    ['teachs_id' => 241, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 242, 'days_id' => 12, 'times_id' => 106, 'rooms_id' => 90],
                    ['teachs_id' => 243, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 92],
                    ['teachs_id' => 244, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 92],
                    ['teachs_id' => 245, 'days_id' => 13, 'times_id' => 105, 'rooms_id' => 92],
                    ['teachs_id' => 246, 'days_id' => 13, 'times_id' => 108, 'rooms_id' => 90],
                    ['teachs_id' => 247, 'days_id' => 14, 'times_id' => 139, 'rooms_id' => 89],
                    ['teachs_id' => 248, 'days_id' => 12, 'times_id' => 140, 'rooms_id' => 92],
                    ['teachs_id' => 249, 'days_id' => 15, 'times_id' => 122, 'rooms_id' => 91],
                    ['teachs_id' => 250, 'days_id' => 15, 'times_id' => 128, 'rooms_id' => 92],
                    ['teachs_id' => 251, 'days_id' => 11, 'times_id' => 111, 'rooms_id' => 92],
                    ['teachs_id' => 252, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 253, 'days_id' => 12, 'times_id' => 119, 'rooms_id' => 91],
                    ['teachs_id' => 254, 'days_id' => 12, 'times_id' => 136, 'rooms_id' => 94],
                    ['teachs_id' => 255, 'days_id' => 13, 'times_id' => 127, 'rooms_id' => 89],
                    ['teachs_id' => 256, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 93],
                    ['teachs_id' => 257, 'days_id' => 14, 'times_id' => 134, 'rooms_id' => 89],
                    ['teachs_id' => 258, 'days_id' => 11, 'times_id' => 121, 'rooms_id' => 92],
                    ['teachs_id' => 259, 'days_id' => 15, 'times_id' => 129, 'rooms_id' => 90],
                    ['teachs_id' => 260, 'days_id' => 15, 'times_id' => 125, 'rooms_id' => 94],
                    ['teachs_id' => 261, 'days_id' => 14, 'times_id' => 103, 'rooms_id' => 89],
                    ['teachs_id' => 262, 'days_id' => 12, 'times_id' => 101, 'rooms_id' => 90],
                    ['teachs_id' => 263, 'days_id' => 14, 'times_id' => 113, 'rooms_id' => 89],
                    ['teachs_id' => 264, 'days_id' => 14, 'times_id' => 129, 'rooms_id' => 89],
                    ['teachs_id' => 266, 'days_id' => 12, 'times_id' => 109, 'rooms_id' => 91],
                    ['teachs_id' => 267, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 90],
                    ['teachs_id' => 271, 'days_id' => 13, 'times_id' => 135, 'rooms_id' => 89],
                    ['teachs_id' => 269, 'days_id' => 14, 'times_id' => 101, 'rooms_id' => 92],
                    ['teachs_id' => 268, 'days_id' => 11, 'times_id' => 128, 'rooms_id' => 94],
                    ['teachs_id' => 272, 'days_id' => 15, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 273, 'days_id' => 15, 'times_id' => 101, 'rooms_id' => 91],
                    ['teachs_id' => 274, 'days_id' => 12, 'times_id' => 125, 'rooms_id' => 93],
                    ['teachs_id' => 275, 'days_id' => 13, 'times_id' => 133, 'rooms_id' => 89],
                    ['teachs_id' => 276, 'days_id' => 12, 'times_id' => 104, 'rooms_id' => 89],
                    ['teachs_id' => 277, 'days_id' => 11, 'times_id' => 122, 'rooms_id' => 90],
                    ['teachs_id' => 278, 'days_id' => 12, 'times_id' => 117, 'rooms_id' => 91],
                    ['teachs_id' => 233, 'days_id' => 11, 'times_id' => 103, 'rooms_id' => 90],
                    ['teachs_id' => 280, 'days_id' => 14, 'times_id' => 126, 'rooms_id' => 92],
                    ['teachs_id' => 281, 'days_id' => 11, 'times_id' => 137, 'rooms_id' => 93],
                    ['teachs_id' => 282, 'days_id' => 14, 'times_id' => 114, 'rooms_id' => 90],
                    ['teachs_id' => 283, 'days_id' => 11, 'times_id' => 126, 'rooms_id' => 90],
                ];


            $allData = [
                $dataToUpdate,
                $dataToUpdate1,
                $dataToUpdate2,
            ];

            $randomData = $allData[array_rand($allData)];

            foreach ($randomData as $data) {
                Schedule::where('value', 1.00)
                    ->where('type', $id)
                    ->where('teachs_id', $data['teachs_id'])
                    ->update(['days_id' => $data['days_id']]);
            }

            foreach ($randomData as &$data) {
                $data['status'] = 0;
            }

            // Hapus data sebelumnya dari tabel Jadwal
            Jadwal::truncate();

            // Insert data baru ke tabel Jadwal
            Jadwal::insertData($randomData);
        } else {


            Jadwal::truncate();

            $schedules = Schedule::where('type', $id)->get();

            // Perulangan untuk menyimpan data ke tabel Mapel
            foreach ($schedules as $schedule) {
                // Buat instance baru dari model Mapel
                $mapel = new Jadwal();

                // Set atribut-atribut yang sesuai
                $mapel->teachs_id = $schedule->teachs_id;
                $mapel->days_id = $schedule->days_id;
                $mapel->times_id = $schedule->times_id;
                $mapel->rooms_id = $schedule->rooms_id;
                $mapel->status = '0';
                // Simpan data ke dalam tabel Mapel
                $mapel->save();
            }
        }



        $notification = array(
            'message' => 'Jadwal Disimpan SuccessFully',
            'alert-type' => 'success'
        );

        // Redirect atau kirim pesan sukses jika diperlukan
        return redirect()->back()->with($notification);
    }
}
