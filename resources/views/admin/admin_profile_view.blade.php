@extends('admin.layouts.master')

@section('content')
    <!-- END: Top Bar -->
    <div class="intro-y flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Profile User
        </h1>
    </div>
    <!-- BEGIN: Profile Info -->

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template" class="rounded-full"
                        src="{{ !empty($adminData->profile_image) ? url('uploads/admin_images/' . $adminData->profile_image) : url('backend/dist/images/profile-9.jpg') }}">

                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $adminData->name }}</div>
                    <div class="text-slate-500">


                        @if ($adminData->role == '1')
                            <span class="dark">Admin</span>
                        @elseif($adminData->role == '2')
                            <span class="dark">Kepala Sekolah</span>
                        @elseif($adminData->role == '3')
                            <span class="dark">Waka Kurikulum</span>
                        @elseif($adminData->role == '4')
                            <span class="dark">Jurusan</span>
                        @elseif($adminData->role == '5')
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
                            class="w-4 h-4 mr-2"></i> {{ $adminData->email }} </div>

                </div>
            </div>

        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="profile-tab" class="nav-item" role="presentation">
                <a href="{{ route('edit.profile') }}" class="nav-link py-4 flex items-center active"
                    data-tw-target="#profile" aria-controls="profile" aria-selected="true" role="tab"> <i
                        class="w-4 h-4 mr-2" data-lucide="user"></i> Update Profile </a>
            </li>
            <li id="change-password-tab" class="nav-item" role="presentation">
                <a href="{{ route('change.password') }}" class="nav-link py-4 flex items-center"
                    data-tw-target="#change-password" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                        data-lucide="lock"></i> Change Password
                </a>
            </li>

        </ul>
    </div>
    <!-- END: Profile Info -->
@endsection
