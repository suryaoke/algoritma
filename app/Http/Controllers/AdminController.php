<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    public function Profile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    } // End Method

    public function EditProfile()
    {

        $id = Auth::user()->id;
        $editData = User::find($id);
        return view('admin.admin_profile_edit', compact('editData'));
    } // End

    public function StoreProfile(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;


        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin_images'), $filename);
            $data['profile_image'] = $filename;
        }

        if ($request->file('ttd')) {
            $file1 = $request->file('ttd');

            $filename1 = date('YmdHi') . $file1->getClientOriginalName();
            $file1->move(public_path('uploads/ttd'), $filename1);
            $data['ttd'] = $filename1;
        }
        $data->save();


        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('admin.profile')->with($notification);
    } // End Method


    public function ChangePassword()
    {

        return view('admin.admin_change_password');
    } // End Method


    public function updatePassword(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|min:8',
            'confirm_password' => 'required|same:newpassword',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the old password matches the current password
        if (Hash::check($request->oldpassword, $user->password)) {
            // Update the user's password
            $user->password = bcrypt($request->newpassword);
            $user->save(); // Save the updated user model

            // Set a success notification message
            $notification = [
                'message' => 'Password updated successfully',
                'alert-type' => 'success', // Use 'success' for a green notification
            ];
        } else {
            // Set an error notification message
            $notification = [
                'message' => 'Old password does not match',
                'alert-type' => 'error', // Use 'error' for a red notification
            ];
        }

        // Flash the notification message and redirect back to the dashboard

        return redirect('/')->with($notification);
    }
}
