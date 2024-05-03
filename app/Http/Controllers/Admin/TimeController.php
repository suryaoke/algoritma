<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Models\Time;
use App\Models\Timenotavailable;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $times = Time::orderBy('id', 'desc')->get();

        return view('admin.time.index', compact('times'));
    }

    public function create(Request $request)
    {
        return view('admin.time.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code_times'  => 'unique:times,code_times|required',
            'time_begin'  => 'required',
            'time_finish' => 'required',
            'jp'         => 'required',
        ]);

        $begin  = $request->input('time_begin');
        $finish = $request->input('time_finish');
        $range  = $request->input('time_begin') . " - " . $request->input('time_finish');

        $params = [
            'code_times'  => $request->input('code_times'),
            'time_begin'  => $begin,
            'time_finish' => $finish,
            'range'       => $range,
            'jp'         => $request->input('jp'),
        ];

        $times = Time::create($params);

        $notification = [
            'message' => 'Jam Create Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.times')->with($notification);
    }

    public function edit($id)
    {
        $times = Time::find($id);

        if ($times == null) {
            return view('admin.layouts.404');
        }

        return view('admin.time.edit', compact('times'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code_times'  => 'required',
            'time_begin'  => 'required',
            'time_finish' => 'required',
            'jp'         => 'required',
        ]);

        $times              = Time::find($id);
        $begin              = $request->input('time_begin');
        $finish             = $request->input('time_finish');
        $range              = $request->input('time_begin') . " - " . $request->input('time_finish');
        $times->code_times  = $request->input('code_times');
        $times->time_begin  = $begin;
        $times->time_finish = $finish;
        $times->range       = $range;
        $times->jp         = $request->input('jp');
        $times->save();

        $notification = [
            'message' => 'Jam Update Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.times')->with($notification);
    }

    public function destroy($id)
    {
        // Cari data Time berdasarkan $id
        $time = Time::find($id);

        if (!$time) {
            return redirect()->route('admin.times')->with('danger', 'Waktu tidak ditemukan');
        }

        // Cek apakah ada data Timenotavailable yang terkait dengan waktu ini
        $timenotavailables = Timenotavailable::where('times_id', $id)->first();

        if ($timenotavailables) {
            // Hapus data Timenotavailable yang terkait terlebih dahulu
            $timenotavailables->delete();
        }

        // Hapus data Time
        $time->delete();
        $notification = [
            'message' => 'Jam delete Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.times')->with($notification);
    }
}
