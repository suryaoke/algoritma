<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan method chaining untuk membangun query
        $users = User::orderBy('role', 'asc');

        if ($request->has('searchname')) {
            $users->where('name', 'LIKE', '%' . $request->input('searchname') . '%');
        }

        if ($request->has('searchemail')) {
            $users->where('email', 'LIKE', '%' . $request->input('searchemail') . '%');
        }

        // Menggunakan metode get() untuk mengeksekusi query dan mendapatkan hasil
        $users = $users->get();

        return view('admin.user.index', compact('users'));
    }


    public function create(Request $request)
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'emailuser' => 'unique:users,email|required',
            'password'  => 'required|confirmed',
            'name'      => 'required',
            'role'      => 'required',

        ]);

        $params = [
            'email'    => $request->input('emailuser'),
            'password' => Hash::make($request->input('password')),
            'name'     => $request->input('name'),
            'role'     => $request->input('role'),
            'status'     =>  $request->input('status'),
        ];

        $users = User::create($params);

        return redirect()->route('admin.user');
    }


    public function destroy($id)
    {
        User::find($id)->delete();

        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus');
    }



    public function UserView($id)
    {

        $user = User::findOrFail($id);
        return view('admin.user.user_view', compact('user'));
    } // End Method


    public function UserEdit($id)
    {

        $editData = User::findOrFail($id);
        return view('admin.user.user_edit', compact('editData'));
    } // End Method



    public function UserUpdate(Request $request)
    {
        $user_id = $request->id;

        // Menggunakan validator untuk memvalidasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required', // Sesuaikan dengan aturan validasi yang sesuai

            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan tipe gambar yang diizinkan dan ukuran maksimal
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle gambar profil jika diunggah
        if ($request->file('profile_image')) {
            $image = $request->file('profile_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/admin_images/', $name_gen);
            $save_url =  $name_gen;
        } else {
            $save_url = ''; // Default jika gambar profil tidak diunggah
        }

        // Simpan data ke dalam database
        $user = User::findOrFail($user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->profile_image = $save_url;
        $user->updated_at = Carbon::now();
        $user->save();

        $notification = [
            'message' => 'User Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.user')->with($notification);
    }


    public function UserTidakAktif($id)
    {



        $user = User::findOrFail($id);
        $user->status = '0';
        $user->save();

        $notification = array(
            'message' => 'User Inactive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function UserAktif($id)
    {

        // $users = User::findOrFail($id);
        // $img = $users->profile_image;
        // unlink($img);

        $user = User::findOrFail($id);
        $user->status = '1';
        $user->save();

        $notification = array(
            'message' => 'User Active Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method



    public function  UserReset(Request $request)
    {

        $user_id = $request->id;
        User::findOrFail($user_id)->update([
            'updated_at' => Carbon::now(),
            'password' => bcrypt($request->password),

        ]);

        $notification = array(
            'message' => 'Reset Password Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


}
