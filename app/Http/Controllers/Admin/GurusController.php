<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;

class GurusController extends Controller
{

    public function index(Request $request)
    {
        $gurus = Guru::orderBy('id', 'desc')->get();

        if (!empty($request->searchname)) {
            $gurus = $gurus->where('name', 'LIKE', '%' . $request->searchname . '%');
        }

        if (!empty($request->searchnidn)) {
            $gurus = $gurus->where('nidn', 'LIKE', '%' . $request->searchnidn . '%');
        }



        return view('admin.guru.index', compact('gurus'));
    }
    public function create(Request $request)
    {
        $users = User::where('role', '5')->pluck('email', 'id');

        return view('admin.guru.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code_gurus' => 'unique:gurus,code_gurus|required',
            'name'           => 'required',
            'akun'  => 'unique:gurus,akun|required',

        ]);

        $params = [
            'code_gurus' => $request->input('code_gurus'),
            'name'           => $request->input('name'),
            'akun'           => $request->input('akun'),

        ];
        $notification = array(
            'message' => 'Guru Create SuccessFully',
            'alert-type' => 'success'
        );

        $gurus = Guru::create($params);

        return redirect()->route('admin.gurus')->with($notification);
    }

    public function edit($id)
    {
        $gurus = Guru::find($id);
        $users = User::where('role', '5')->pluck('email', 'id');
        if ($gurus == null) {
            return view('admin.layouts.404');
        }

        return view('admin.guru.edit', compact('gurus', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code_gurus' => 'unique:gurus,code_gurus,' . $id . '|required',
            'name'           => 'required',
            'akun'  => 'required',

        ]);

        $gurus                 = Guru::find($id);
        $gurus->code_gurus = $request->input('code_gurus');
        $gurus->name           = $request->input('name');
        $gurus->akun        = $request->input('akun');
        $gurus->save();

        $notification = array(
            'message' => 'Guru Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.gurus')->with($notification);
    }

    public function destroy($id)
    {
        Guru::find($id)->delete();

        $notification = array(
            'message' => 'Guru Deleted SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.gurus')->with($notification);
    }
}
