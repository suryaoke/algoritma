<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Guru;
use App\Models\Time;
use App\Models\Timenotavailable;
use Illuminate\Http\Request;

class TimenotavailableController extends Controller
{

    public function index(Request $request)
    {

        $timenotavailables = Timenotavailable::orderBy('id', 'desc')->get();

        return view('admin.timenotavailable.index', compact('timenotavailables'));
    }

    public function create(Request $request)
    {

        $gurus = Guru::orderBy('name', 'asc')->pluck('name', 'id');
        $days      = Day::orderBy('name_day', 'asc')->pluck('name_day', 'id');
        $times     = Time::orderBy('range', 'asc')->pluck('range', 'id');



        return view('admin.timenotavailable.create', compact('gurus', 'days', 'times'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'gurus' => 'required',
            'days'      => 'required',
            'times'     => 'required',

        ]);

        $params = [
            'gurus_id' => $request->input('gurus'),
            'days_id'      => $request->input('days'),
            'times_id'     => $request->input('times'),
        ];

        $timenotavailables = Timenotavailable::create($params);
        $notification = array(
            'message' => 'Waktu Berhalangan Create SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.timenotavailables')->with($notification);
    }

    public function edit($id)
    {
        $timenotavailables = Timenotavailable::find($id);
        $gurus         = Guru::orderBy('name', 'asc')->pluck('name', 'id');
        $days              = Day::orderBy('name_day', 'asc')->pluck('name_day', 'id');
        $times             = Time::orderBy('range', 'asc')->pluck('range', 'id');

        if ($timenotavailables == null) {
            return view('admin.layouts.404');
        }

        return view('admin.timenotavailable.edit', compact('timenotavailables', 'gurus', 'days', 'times'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'gurus' => 'required',
            'days'      => 'required',
            'times'     => 'required',
        ]);

        $timenotavailables               = Timenotavailable::find($id);
        $timenotavailables->gurus_id = $request->input('gurus');
        $timenotavailables->days_id      = $request->input('days');
        $timenotavailables->times_id     = $request->input('times');
        $timenotavailables->save();
        $notification = array(
            'message' => 'Waktu Berhalangan Update SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.timenotavailables')->with($notification);
    }

    public function destroy($id)
    {
        Timenotavailable::find($id)->delete();
        $notification = array(
            'message' => 'Waktu Berhalangan Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.timenotavailables')->with($notification);
    }
}
