<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Guru;
use App\Models\Teach;
use Illuminate\Http\Request;

class TeachController extends Controller
{

    public function index(Request $request)
    {
        $searchGurus = $request->input('searchgurus');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Teach::query();

        // Filter berdasarkan nama guru jika searchgurus tidak kosong
        if (!empty($searchGurus)) {
            $query->whereHas('guru', function ($guruQuery) use ($searchGurus) {
                $guruQuery->where('name', 'LIKE', '%' . $searchGurus . '%');
            });
        }

        // Filter berdasarkan nama mata kuliah jika searchcourse tidak kosong
        if (!empty($searchCourse)) {
            $query->whereHas('course', function ($courseQuery) use ($searchCourse) {
                $courseQuery->where('name', 'LIKE', '%' . $searchCourse . '%');
            });
        }

        // Filter berdasarkan nama kelas jika searchclass tidak kosong
        if (!empty($searchClass)) {
            $query->where('class_room', 'LIKE', '%' . $searchClass . '%');
        }

        // Lakukan pengurutan data berdasarkan id secara descending
        $teachs = $query->orderBy('class_room', 'asc')->get();

        return view('admin.teach.index', compact('teachs'));
    }


    public function create(Request $request)
    {
        $gurus = Guru::orderBy('name', 'asc')->pluck('name', 'id');
        $courses   = Course::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.teach.create', compact('gurus', 'courses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'roomclass' => 'required',
            'year'      => 'required',
            'gurus' => 'required',
            'courses'   => 'required',
        ]);

        // Cek apakah kombinasi yang sama sudah ada dalam basis data
        $existingTeach = Teach::where([
            'class_room'   => $request->input('roomclass'),
            'year'         => $request->input('year'),
            'gurus_id' => $request->input('gurus'),
            'courses_id'   => $request->input('courses'),
        ])->first();

        if ($existingTeach) {
            // Jika kombinasi sudah ada, tampilkan pesan kesalahan
            $notification = array(
                'message' => 'Pengampu Sudah Ada',
                'alert-type' => 'warning'
            );

            return redirect()->back()->with($notification);
        }

        $params = [
            'class_room'   => $request->input('roomclass'),
            'year'         => $request->input('year'),
            'gurus_id' => $request->input('gurus'),
            'courses_id'   => $request->input('courses'),
        ];

        $notification = array(
            'message' => 'Pengampu Create SuccessFully',
            'alert-type' => 'success'
        );
        $teachs = Teach::create($params);

        return redirect()->route('admin.teachs')->with($notification);
    }

    public function edit($id)
    {
        $teachs    = Teach::find($id);
        $gurus = Guru::orderBy('name', 'asc')->pluck('name', 'id');
        $courses   = Course::orderBy('name', 'asc')->pluck('name', 'id');

        if ($teachs == null) {
            return view('admin.layouts.404');
        }

        return view('admin.teach.edit', compact('teachs', 'gurus', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'roomclass' => 'required',
            'year'      => 'required',
            'gurus' => 'required',
            'courses'   => 'required',
        ]);

        $teachs               = Teach::find($id);
        $teachs->class_room   = $request->input('roomclass');
        $teachs->year         = $request->input('year');
        $teachs->gurus_id = $request->input('gurus');
        $teachs->courses_id   = $request->input('courses');
        $teachs->save();

        $notification = array(
            'message' => 'Pengampu Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.teachs')->with($notification);
    }

    public function destroy($id)
    {
        Teach::find($id)->delete();

        $notification = array(
            'message' => 'Pengampu Delete SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.teachs')->with($notification);
    }
}
