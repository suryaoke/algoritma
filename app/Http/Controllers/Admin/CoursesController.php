<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Jurusan;
use App\Models\Teach;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
      

        if (!empty($request->searchcode)) {
            $code = $query->where('code_courses', 'LIKE', '%' . $request->searchcode . '%');
        }

        if (!empty($request->searchname)) {
            $nmae = $query->where('name', 'LIKE', '%' . $request->searchname . '%');
        }
        $courses = $query->orderBy('id', 'desc')->get();


        return view('admin.courses.index', compact('courses'));
    }

    public function create(Request $request)
    {
        $jurusan = Jurusan::pluck('name', 'id');
        $type = [
            'Teori'     => 'Teori',
            'Praktikum' => 'Praktikum',
        ];

        return view('admin.courses.create', compact('type', 'jurusan'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code_courses' => 'unique:courses,code_courses|required',
            'namecourses'  => 'required',
            'jp'          => 'required',
            'semester'     => 'required',
            'jurusan'     => 'required',

        ]);

        $params = [
            'code_courses' => $request->input('code_courses'),
            'name'         => $request->input('namecourses'),
            'jp'          => $request->input('jp'),
            'semester'     => $request->input('semester'),
            'type'         => $request->input('type'),
            'jurusan'         => $request->input('jurusan'),
        ];

        $courses = Course::create($params);
        $notification = array(
            'message' => 'Mata Pelajaran Create SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.courses')->with($notification);
    }

    public function edit($id)
    {
        $courses = Course::find($id);

        if ($courses == null) {
            return view('admin.layouts.404');
        }
        $jurusan = Jurusan::pluck('name', 'id');
        $type = array(
            'Teori'     => 'Teori',
            'Praktikum' => 'Praktikum'
        );

        return view('admin.courses.edit', compact('courses', 'type', 'jurusan'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'code_courses' => 'unique:courses,code_courses,' . $id . '|required',
            'namecourses'  => 'required',
            'jp'          => 'required',
            'semester'     => 'required',
            'jurusan'     => 'required',

        ]);

        $courses               = Course::find($id);
        $courses->code_courses = $request->input('code_courses');
        $courses->name         = $request->input('namecourses');
        $courses->jp          = $request->input('jp');
        $courses->semester     = $request->input('semester');
        $courses->type         = $request->input('type');
        $courses->jurusan         = $request->input('jurusan');
        $courses->save();

        $notification = array(
            'message' => 'Mata Pelajaran Update SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.courses')->with($notification);
    }

    public function destroy($id)
    {
        $teachs = Teach::where('courses_id', $id)->first();

        if (!empty($teachs)) {
            return redirect()->route('admin.courses')->with('danger', 'Anda Harus Menghapus Data Pengampu yang Berhubungan Dengan Mata Kuliah Ini Terlebih Dahulu');
        } else {
            Course::find($id)->delete();
        }

        $notification = array(
            'message' => 'Mata Pelajaran Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.courses')->with($notification);
    }
}
