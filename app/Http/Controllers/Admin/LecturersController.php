<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;

class LecturersController extends Controller
{

    public function index(Request $request)
    {
        $lecturers = Lecturer::orderBy('id', 'desc')->get();

        if (!empty($request->searchname)) {
            $lecturers = $lecturers->where('name', 'LIKE', '%' . $request->searchname . '%');
        }

        if (!empty($request->searchnidn)) {
            $lecturers = $lecturers->where('nidn', 'LIKE', '%' . $request->searchnidn . '%');
        }



        return view('admin.lecturer.index', compact('lecturers'));
    }
    public function create(Request $request)
    {
        $users = User::where('role', '5')->pluck('email', 'id');

        return view('admin.lecturer.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code_lecturers' => 'unique:lecturers,code_lecturers|required',
            'name'           => 'required',
            'alamat'  => 'required',
            'no_hp'  => 'required',
            'akun'  => 'unique:lecturers,akun|required',

        ]);

        $params = [
            'code_lecturers' => $request->input('code_lecturers'),
            'name'           => $request->input('name'),
            'alamat'           => $request->input('alamat'),
            'no_hp'           => $request->input('no_hp'),
            'akun'           => $request->input('akun'),

        ];
        $notification = array(
            'message' => 'Guru Create SuccessFully',
            'alert-type' => 'success'
        );

        $lecturers = Lecturer::create($params);

        return redirect()->route('admin.lecturers')->with($notification);
    }

    public function edit($id)
    {
        $lecturers = Lecturer::find($id);
        $users = User::where('role', '5')->pluck('email', 'id');
        if ($lecturers == null) {
            return view('admin.layouts.404');
        }

        return view('admin.lecturer.edit', compact('lecturers', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code_lecturers' => 'unique:lecturers,code_lecturers,' . $id . '|required',
            'name'           => 'required',
            'alamat'  => 'required',
            'no_hp'  => 'required',
            'akun'  => 'required',

        ]);

        $lecturers                 = Lecturer::find($id);
        $lecturers->code_lecturers = $request->input('code_lecturers');
        $lecturers->name           = $request->input('name');
        $lecturers->alamat          = $request->input('alamat');
        $lecturers->no_hp          = $request->input('no_hp');
        $lecturers->akun        = $request->input('akun');
        $lecturers->save();

        $notification = array(
            'message' => 'Guru Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.lecturers')->with($notification);
    }

    public function destroy($id)
    {
        Lecturer::find($id)->delete();

        $notification = array(
            'message' => 'Guru Deleted SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.lecturers')->with($notification);
    }
}
