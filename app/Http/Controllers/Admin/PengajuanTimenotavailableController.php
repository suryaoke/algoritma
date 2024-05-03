<?php

namespace App\Http\Controllers\admin;

use App\Models\Day;
use App\Models\Guru;
use App\Models\Time;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTimenotavailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengajuanTimenotavailableController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $role = Auth::user()->role; // Mengambil peran pengguna

        $timenotavailables = PengajuanTimenotavailable::whereHas('guru', function ($query) use ($userId, $role) {
            if ($role != 4) {
                $query->where('akun', $userId);
            }
        })->get();


        return view('admin.pengajuantimenotavailable.index', compact('timenotavailables'));
    }

    public function create(Request $request)
    {

        $gurus = Guru::orderBy('name', 'asc')->where('akun', Auth::user()->id)->pluck('name', 'id');
        $days      = Day::orderBy('name_day', 'desc')->pluck('name_day', 'id');
        $times     = Time::orderBy('range', 'asc')->pluck('range', 'id');

        return view('admin.pengajuantimenotavailable.create', compact('gurus', 'days', 'times'));
    }


    public function store(Request $request)
    {
        // Validasi data yang masuk dari formulir
        $validator = Validator::make($request->all(), [
            'gurus_id' => 'required',
            'days'      => 'required',
            'times'     => 'required',
         
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil, simpan data ke dalam database
        $timenotavailable = new PengajuanTimenotavailable();
        $timenotavailable->gurus_id = $request->input('gurus_id');
        $timenotavailable->days_id = $request->input('days');
        $timenotavailable->times_id = $request->input('times');
        $timenotavailable->status = 1;
        $timenotavailable->save();

        $notification = array(
            'message' => 'Waktu Berhalangan Create SuccessFully',
            'alert-type' => 'success'
        );

        // Redirect ke halaman lain atau tampilkan pesan sukses
        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }



    public function edit($id)
    {
        $timenotavailables = PengajuanTimenotavailable::find($id);
        $gurus = Guru::orderBy('name', 'asc')->where('akun', Auth::user()->id)->pluck('name', 'id');
        $days              = Day::orderBy('name_day', 'asc')->pluck('name_day', 'id');
        $times             = Time::orderBy('range', 'asc')->pluck('range', 'id');

        if ($timenotavailables == null) {
            return view('admin.layouts.404');
        }

        return view('admin.pengajuantimenotavailable.edit', compact('timenotavailables', 'gurus', 'days', 'times'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'gurus' => 'required',
            'days'      => 'required',
            'times'     => 'required',

        ]);

        $timenotavailables               = PengajuanTimenotavailable::find($id);
        $timenotavailables->gurus_id = $request->input('gurus');
        $timenotavailables->days_id      = $request->input('days');
        $timenotavailables->times_id     = $request->input('times');
        $timenotavailables->save();

        $notification = array(
            'message' => 'Waktu Berhalangan Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }

    public function destroy($id)
    {
        PengajuanTimenotavailable::find($id)->delete();
        $notification = array(
            'message' => 'Pengajuan Delete SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }



    public function Ditolak($id)
    {

        // $users = User::findOrFail($id);
        // $img = $users->profile_image;
        // unlink($img);

        $user = PengajuanTimenotavailable::findOrFail($id);
        $user->status = '3';
        $user->save();

        $notification = array(
            'message' => 'Pengajuan Ditolak Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function Diterima($id)
    {

        // $users = User::findOrFail($id);
        // $img = $users->profile_image;
        // unlink($img);

        $user = PengajuanTimenotavailable::findOrFail($id);
        $user->status = '2';
        $user->save();

        $notification = array(
            'message' => 'Pengajuan Diterima Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method



}
