@extends('admin.layouts.master')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="intro-y flex items-center mt-8 mb-4">
        <h1 class="text-lg font-medium mr-auto">
            Edit Profile User
        </h1>
    </div>

    <form method="post" action="{{ route('store.profile') }}" enctype="multipart/form-data">
        @csrf

        <div> <label for="regular-form-1" class="form-label">Name</label> <input name="name" id="regular-form-1"
                type="text" class="form-control" value="{{ $editData->name }}"> </div>
        <div class="mt-3"> <label for="regular-form-1" class="form-label">User Email</label> <input name="email"
                id="regular-form-1" type="text" class="form-control" value="{{ $editData->email }}"> </div>



        <div class="mt-3 flex"> <label for="regular-form-1" class="mr-2">Profile Image</label> <input name="profile_image"
                type="file" id="image">
{{--  
            @if ($editData->role == '1')
                <label for="regular-form-1" class="mr-2">Ttd Image</label> <input name="ttd" type="file"
                    id="ttd">
            @endif  --}}

        </div>


        <div class="mt-3 flex ">
            <img width="130px auto" id="showImage"
                src="{{ !empty($editData->profile_image) ? url('uploads/admin_images/' . $editData->profile_image) : url('uploads/no_image.jpg') }}"
                alt="Card image cap">
            {{--  @if ($editData->role == '1')
                <div class="ml-8">
                    <img width="130px auto" id="showImage1"
                        src="{{ !empty($editData->ttd) ? url('uploads/ttd/' . $editData->ttd) : url('uploads/no_image.jpg') }}"
                        alt="Card image cap">
                </div>
            @endif  --}}
        </div>

        <input type="submit" name="profile_image" class="btn btn-primary waves-effect waves-light mt-6"
            value="Update Profile">
    </form>



    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#ttd').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage1').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
