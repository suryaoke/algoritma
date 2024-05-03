@extends('admin.layouts.master')

@section('content')
    <!-- END: Top Bar -->
    <div class="intro-y flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Edit User
        </h1>
    </div>
    <!-- BEGIN: Profile Info -->


    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template" class="rounded-full"
                        src="{{ !empty($user->profile_image) ? url('uploads/admin_images/' . $user->profile_image) : url('backend/dist/images/profile-9.jpg') }}">

                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $user->name }}</div>
                    <div class="text-slate-500">
                        @if ($user->role == '1')
                            <span class="dark">Admin</span>
                        @elseif($user->role == '2')
                            <span class="dark">Kepala Sekolah</span>
                        @elseif($user->role == '3')
                            <span class="dark">Waka Kurikulum</span>
                        @elseif($user->role == '4')
                            <span class="dark">Jurusan</span>
                        @elseif($user->role == '5')
                            <span class="dark">Guru</span>
                        @endif
                    </div>
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-lucide="mail"
                            class="w-4 h-4 mr-2"></i>
                        {{ $user->email }} </div>

                </div>
            </div>

        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="profile-tab" class="nav-item" role="presentation">
                <a href="{{ route('user.edit', $user->id) }}" class="nav-link py-4 flex items-center active"
                    data-tw-target="#profile" aria-controls="profile" aria-selected="true" role="tab"> <i
                        class="w-4 h-4 mr-2" data-lucide="user"></i> Edit User </a>
            </li>

            <li id="change-password-tab" class="nav-item" role="presentation">
                <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#button-modal-preview1"
                    class="nav-link py-4 flex items-center" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                        data-lucide="lock"></i> Reset Password
                </a>
            </li>

        </ul>
    </div>
    <!-- END: Profile Info -->


    <div id="button-modal-preview1" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                        class="w-8 h-8 text-slate-400"></i> </a>
                <div class="modal-body p-0">
                    <div class="p-5 text-center"> <i data-lucide="check-circle"
                            class="w-16 h-16 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Reset Password</div>
                        <div class="text-slate-500 mt-2">Default Password : 12345678</div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form method="post" action="{{ route('user.reset') }}" id="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input name="id" id="regular-form-1" type="hidden" class="form-control"
                                value="{{ $user->id }}">
                            <input name="password" id="regular-form-1" type="hidden" class="form-control" value="12345678">
                            <input type="submit" name="profile_image" class="btn btn-danger waves-effect waves-light mt-2 "
                                value="Reset">


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
