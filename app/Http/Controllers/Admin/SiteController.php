<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Day;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Guru;
use App\Models\PengajuanTimenotavailable;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teach;
use App\Models\Time;
use App\Models\Timenotavailable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $users     = User::count();
        $courses   = Course::count();
        $days      = Day::count();
        $gurus = Guru::count();
        $rooms     = Room::count();
        $teachs    = Teach::count();
        $times     = Time::count();
        $schedules = Jadwal::count();
        $berhalangan = Timenotavailable::count();

        $jadwal = Jadwal::count();

        $userId = Auth::user()->id; // Mendapatkan ID login pengguna

        $jadwalguru = Jadwal::join('teachs', 'jadwals.teachs_id', '=', 'teachs.id')
            ->join('gurus', 'teachs.gurus_id', '=', 'gurus.id')
            ->where('gurus.akun', '=', $userId)
            ->count();
        $pengajuanguru = PengajuanTimenotavailable::whereHas('guru', function ($query) use ($userId) {
            $query->where('akun', $userId);
        })->count();

        $jurusan = Jurusan::count();

        return view('admin.site.admin', compact('jurusan', 'berhalangan', 'pengajuanguru', 'jadwalguru', 'users', 'courses', 'days', 'gurus', 'rooms', 'teachs', 'times', 'schedules'));
    }
}
