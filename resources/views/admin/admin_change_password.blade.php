@extends('admin.layouts.master')

@section('content')
    <div class="intro-y flex items-center mt-8 mb-4">
        <h1 class="text-lg font-medium mr-auto">
            Change Password
        </h1>


    </div>

    <form method="post" action="{{ route('update.password') }}" enctype="multipart/form-data">
        @csrf

        <div> <label for="regular-form-1" class="form-label">Old Password</label> <input name="oldpassword" id="oldpassword"
                type="password" class="form-control"> </div>
        <div class="mt-3"> <label for="regular-form-1" class="form-label">New Password</label> <input name="newpassword"
                id="newpassword" type="password" class="form-control"> </div>
        <div class="mt-3"> <label for="regular-form-1" class="form-label">Confirm Password </label> <input
                name="confirm_password" id="confirm_password" type="password" class="form-control"> </div>
        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}"
                class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
            <button class="btn btn-primary  py-3 px-4    xl:w-32 xl:mr-3 align-top" type="submit">Change</button>
        </div>

    </form>
@endsection
