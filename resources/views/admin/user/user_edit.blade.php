@extends('admin.layouts.master')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="intro-y flex editDatas-center mt-8 mb-4">
        <h1 class="text-lg font-medium mr-auto">
            Edit Profile User
        </h1>
    </div>

    <form method="post" action="{{ route('user.update') }}" id="myForm" enctype="multipart/form-data">
        @csrf
        <input name="id" id="regular-form-1" type="hidden" class="form-control" value="{{ $editData->id }}">

        <div class="mt-3">
            <label for="regular-form-1" class="form-label">Name</label>
            <input name="name" id="regular-form-1" type="text" class="form-control" value="{{ $editData->name }}">
        </div>
        <div class="mt-3">
            <label for="regular-form-1" class="form-label">User Email</label>
            <input name="email" id="regular-form-1" type="text" class="form-control" value="{{ $editData->email }}">
        </div>

        <div class="mt-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" data-placeholder="Select Role" class="tom-select w-full mt-2">
                <option value="{{ $editData->role }}">

                    @if ($editData->role == '0')
                        Tidak Aktif
                    @elseif($editData->role == '1')
                        admin
                    @elseif($editData->role == '2')
                        Kepala Sekolah
                    @elseif($editData->role == '3')
                        Wakil Kurikulum
                    @elseif($editData->role == '4')
                        Jurusan
                    @elseif($editData->role == '4')
                        Guru
                    @endif
                </option>

                <option value="0">Tidak Aktif</option>
                <option value="1">Admin</option>
                <option value="2">Kepala Sekolah</option>
                <option value="3">Wakil Kurikulum</option>
                <option value="4">Jurusan</option>
                <option value="5">Guru</option>
            </select>
        </div>

        <div class="mt-3 flex">
            <label for="image" class="mr-2">Profile Image</label>
            <input name="profile_image" type="file" id="image">
        </div>

        <div class="mt-3 flex">

            <img width="130px" id="showImage"
                src="{{ !empty($editData->profile_image) ? url('uploads/admin_images/' . $editData->profile_image) : url('uploads/no_image.jpg') }}"
                alt="Profile Image">
        </div>

        <input type="submit" name="update_profile" class="btn btn-primary waves-effect waves-light mt-6"
            value="Update User">
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    role: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: 'Please Enter Your Name',
                    },
                    email: {
                        required: 'Please Enter a Valid Email Address',
                        email: 'Please Enter a Valid Email Address',
                    },
                    role: {
                        required: 'Please Select a Role',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });

        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>
@endsection
