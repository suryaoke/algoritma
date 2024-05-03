<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::orderBy('code_rooms', 'asc')->get();

        if (!empty($request->searchcode)) {
            $rooms = $rooms->where('code_rooms', 'LIKE', '%' . $request->searchcode . '%');
        }

        if (!empty($request->searchname)) {
            $rooms = $rooms->where('name', 'LIKE', '%' . $request->searchname . '%');
        }



        return view('admin.room.index', compact('rooms'));
    }

    public function create(Request $request)
    {

        $type = array(
            'Teori'        => 'Teori',
            'Praktikum'   => 'Pratikum'
        );
        $jurusan = Jurusan::pluck('name', 'id');

        return view('admin.room.create', compact('type', 'jurusan'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code_rooms' => 'unique:rooms,code_rooms|required',
            'namerooms'  => 'required',
            'jurusan'   => 'required',
        ]);

        $params = [
            'code_rooms' => $request->input('code_rooms'),
            'name'       => $request->input('namerooms'),
            'type'       => $request->input('type'),
            'jurusan'       => $request->input('jurusan'),
        ];

        $notification = array(
            'message' => 'Ruangan Create SuccessFully',
            'alert-type' => 'success'
        );
        $rooms = Room::create($params)->with($notification);

        return redirect()->route('admin.rooms');
    }

    public function edit($id)
    {
        $rooms = Room::find($id);

        $jurusan = Jurusan::pluck('name', 'id');

        if ($rooms == null) {
            return view('admin.layouts.404');
        }

        $type = array(
            'Teori'        => 'Teori',
            'Praktikum'   => 'Pratikum'
        );

        return view('admin.room.edit', compact('rooms', 'type', 'jurusan'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'code_rooms' => 'unique:rooms,code_rooms,' . $id . '|required',
            'namerooms'  => 'required',
            'jurusan'   => 'required',
        ]);

        $rooms             = Room::find($id);
        $rooms->code_rooms = $request->input('code_rooms');
        $rooms->name       = $request->input('namerooms');
        $rooms->type       = $request->input('type');
        $rooms->jurusan    = $request->input('jurusan');
        $rooms->save();

        $notification = array(
            'message' => 'Ruangan Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.rooms')->with($notification);
    }

    public function destroy($id)
    {
        Room::find($id)->delete();

        $notification = array(
            'message' => 'Ruangan Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.rooms')->with($notification);
    }
}
