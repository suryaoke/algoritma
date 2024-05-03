<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $jurusans = Jurusan::orderBy('id', 'desc')->get();


        return view('admin.jurusan.index', compact('jurusans'));
    }

    public function create(Request $request)
    {


        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
        ]);

        $params = [
            'name' => $request->input('name'),
        ];

        $jurusan = Jurusan::create($params);

        if ($jurusan) {
            $notification = [
                'message' => 'Jurusan berhasil dibuat',
                'alert-type' => 'success',
            ];
        } else {
            $notification = [
                'message' => 'Gagal membuat jurusan',
                'alert-type' => 'error',
            ];
        }

        return redirect()->route('admin.jurusans')->with($notification);
    }


    public function edit($id)
    {
        $jurusans = Jurusan::find($id);


        return view('admin.jurusan.edit', compact('jurusans'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'  => 'required',
            
        ]);

        $jurusans             = Jurusan::find($id);
        $jurusans->name       = $request->input('name');
        $jurusans->save();

        $notification = array(
            'message' => 'Jurusan Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.jurusans')->with($notification);
    }

    public function destroy($id)
    {
        Jurusan::find($id)->delete();

        $notification = array(
            'message' => 'Jurusan Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.jurusans')->with($notification);
    }
}
