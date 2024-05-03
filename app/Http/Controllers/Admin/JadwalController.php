<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Export;
use App\Exports\SchedulesExport;
use App\Exports\SchedulesExport1;
use App\Exports\TestExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Day;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teach;
use App\Models\Time;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use PDF;

class JadwalController extends Controller
{
    public function all(Request $request)
    {

        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchGurus = $request->input('searchgurus');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

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


        $schedules = $query->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();
        $guru = Guru::all();
        $course = Course::all();

        $pengampu = Teach::orderby('id', 'asc')
            ->whereNotIn('id', function ($query) {
                $query->select('teachs_id')
                    ->from('jadwals');
            })
            ->get();
        // $pengampu = Teach::all();


        return view('admin.jadwal.jadwal_all', compact('pengampu', 'room', 'course', 'guru', 'day', 'time', 'room', 'schedules'));
    }



    public function destroy($id)
    {
        $schedule = Jadwal::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Jadwal Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function updateStatus()
    {
        Jadwal::where('status', '=', 0)
            ->orWhere('status', '=', 3)
            ->update(['status' => 1]);
        $notification = array(
            'message' => 'Jadwal Berhasil Di Kirim SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



    public function statusOne($id)
    {
        $schedule = Jadwal::find($id);
        $schedule->status = '1';
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Berhasil Di Kirim SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function allKepsek(Request $request)
    {
        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchGurus = $request->input('searchgurus');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

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

        // Mengambil data jadwal berdasarkan 'status'
        $schedules = $query->whereIn('status', [1, 2, 3])
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();

        $day = Day::all();
        $time = Time::all();
        $room = Room::all();

        return view('admin.jadwal.jadwal_kepsek', compact('day', 'time', 'room', 'schedules'));
    }



    public function updateVerifikasi()
    {
        Jadwal::where('status', '=', 1)
            ->update(['status' => 2]);
        $notification = array(
            'message' => 'Jadwal Berhasil Di Verifikasi SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function verifikasiOne($id)
    {
        $schedule = Jadwal::find($id);
        $schedule->status = '2';
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Berhasil Di Verifikasi SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method




    public function allGuru(Request $request)
    {

        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchGurus = $request->input('searchgurus');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

        // Filter berdasarkan nama hari jika searchgurus tidak kosong
        if (!empty($searchDays)) {
            $query->whereHas('day', function ($guruQuery) use ($searchDays) {
                $guruQuery->where('name_day', 'LIKE', '%' . $searchDays . '%');
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
        $userId = Auth::user()->id;

        $schedules = $query
            ->join(
                'teachs',
                'jadwals.teachs_id',
                '=',
                'teachs.id'
            )
            ->join('gurus', 'teachs.gurus_id', '=', 'gurus.id')
            ->where('status', '2')
            ->where('gurus.akun', '=', $userId)
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();
        return view('admin.jadwal.jadwal_guru', compact('day', 'time', 'room', 'schedules'));
    } // end method




    public function verifikasiTolak($id)
    {
        $schedule = Jadwal::find($id);
        $schedule->status = '3';
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Berhasil Di Tolak SuccessFully',
            'alert-type' => 'danger'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function excelJadwal()
    {
        $schedules = Jadwal::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'jadwals.teachs_id', '=', 'teachs.id')
            ->orderBy('teachs.class_room', 'asc') // Urutkan berdasarkan class_room terkecil
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();

        $export = new SchedulesExport1($schedules);
        return Excel::download($export, 'JadwalAlgoritma.xlsx');
    }



    public function pdfJadwal()
    {
        $schedules = Jadwal::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'jadwals.teachs_id', '=', 'teachs.id')
            ->join('times', 'jadwals.times_id', '=', 'times.id')
            // Urutkan berdasarkan class_room terkecil
            ->orderBy('teachs.class_room', 'asc')
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')

            ->get();



        $pdf = PDF::loadView('admin.jadwal.pdf', ['schedules' => $schedules]); // Pastikan Anda mengirimkan data dalam bentuk array assosiatif
        return $pdf->download('invoice.pdf');
    }

    public function pdfJadwalCustom(Request $request)
    {
        $kelas =  $request->input('kelas');
        $semester =  $request->input('semester');

        $schedules = Jadwal::with('day', 'time', 'room', 'teach.course', 'teach.guru')
            ->join('teachs', 'jadwals.teachs_id', '=', 'teachs.id')
            ->join('times', 'jadwals.times_id', '=', 'times.id')
            ->join('courses', 'teachs.courses_id', '=', 'courses.id')
            ->orderBy('teachs.class_room', 'asc')
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->where('teachs.class_room', $kelas)
            ->where('courses.semester', $semester)
            ->get();

        $pdf = PDF::loadView('admin.jadwal.pdf_custom', ['schedules' => $schedules, 'semester' => $semester, 'kelas' => $kelas]); // Pastikan Anda mengirimkan data dalam bentuk array asosiatif
        return $pdf->download('invoice.pdf');
    }

    public function jadwalMapelStore(Request $request)
    {
        $guruInput = Teach::where('id', $request->teachs_id)->first();
        $guru = DB::table('jadwals')
            ->join('teachs', 'teachs.id', '=', 'jadwals.teachs_id')
            ->select('teachs.class_room', 'teachs.gurus_id')
            ->first();

        if (!$guru) {
            Jadwal::insert([
                'teachs_id' => $request->teachs_id,
                'days_id' => $request->days_id,
                'times_id' => $request->times_id,
                'rooms_id' => $request->rooms_id,
                'status' => '0',

            ]);

            $notification = array(
                'message' => 'Jadwal Inserted Successfully kode ',
                'alert-type' => 'success'
            );
            return redirect()->route('jadwal.all')->with($notification);
        }



        if ($guruInput->gurus_id == $guru->gurus_id || $guruInput->class_room == $guru->class_room) {
            $existingData = Jadwal::where('days_id', $request->days_id)
                ->where('times_id', $request->times_id)
                ->count();

            if ($existingData > 0) {
                $notification = array(
                    'message' => 'Data Jadwal Mapel Bentrok..!!',
                    'alert-type' => 'warning'
                );
                return redirect()->back()->with($notification);
            } else {
                Jadwal::insert([
                    'teachs_id' => $request->teachs_id,
                    'days_id' => $request->days_id,
                    'times_id' => $request->times_id,
                    'rooms_id' => $request->rooms_id,
                    'status' => '0',

                ]);

                $notification = array(
                    'message' => 'Jadwal Inserted Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('jadwal.all')->with($notification);
            }
        } else {
            Jadwal::insert([
                'teachs_id' => $request->teachs_id,
                'days_id' => $request->days_id,
                'times_id' => $request->times_id,
                'rooms_id' => $request->rooms_id,
                'status' => '0',

            ]);

            $notification = array(
                'message' => 'Jadwal Inserted Successfully kode ',
                'alert-type' => 'success'
            );
            return redirect()->route('jadwal.all')->with($notification);
        }
    }


    public function JadwalmapelUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'days_id' =>  'required',
            'times_id' => 'required',
            'rooms_id' => 'required',
        ]);

        // Mengambil data jadwal berdasarkan $id
        $jadwalmapel = Jadwal::find($id);

        // Cek untuk memastikan tidak ada bentrok jadwal saat memperbarui
        $existingData = Jadwal::where('days_id', $request->days_id)
            ->where('times_id', $request->times_id)
            ->where('id', '!=', $id) // untuk menghindari membandingkan dengan dirinya sendiri
            ->count();

        // Jika ada bentrok, kirim notifikasi
        if ($existingData > 0) {
            $notification = array(
                'message' => 'Data Jadwal Mapel Bentrok ..!!',
                'alert-type' => 'warning'
            );
            return redirect()->back()->with($notification);
        }

        // Lakukan pembaruan pada nilai kolom-kolom jadwal berdasarkan data yang diterima dari formulir
        $jadwalmapel->days_id = $request->input('days_id');
        $jadwalmapel->times_id = $request->input('times_id');
        $jadwalmapel->rooms_id = $request->input('rooms_id');
        // Tambahkan pembaruan lain sesuai kebutuhan Anda

        // Menyimpan perubahan ke dalam database
        $jadwalmapel->save();

        $notification = array(
            'message' => 'Jadwal  Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function tesExport(Request $request)
    {
        return Excel::download(new TestExport , 'algoritma.xlsx');
    }
}
