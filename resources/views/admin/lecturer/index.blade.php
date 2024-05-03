@extends('admin.layouts.master')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="page-content">
        <div class="mb-3 intro-y flex flex-col sm:flex-row items-center mt-8">
            <h1 class="text-lg font-medium mr-auto">Data Guru Semua</h1>
            {!! Form::open(['role' => 'form', 'route' => 'admin.lecturers', 'method' => 'get', 'class' => 'sm:flex']) !!}
            @if (Auth::user()->role == '1')
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <a href="{{ route('admin.lecturer.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Data</a>
                </div>
            @endif
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    {!! Form::text('searchnidn', request('searchnidn'), [
                        'class' => 'form-control',
                        'placeholder' => 'Berdasarkan kode',
                    ]) !!}
                </div>
            </div>
            <div class="flex-1 sm:ml-2">
                <div class="form-group">
                    {!! Form::text('searchname', request('searchname'), [
                        'class' => 'form-control',
                        'placeholder' => 'Berdasarkan Nama',
                    ]) !!}
                </div>
            </div>
            <div class="sm:ml-2">
                {!! Form::submit('Cari', ['class' => 'btn btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="overflow-x-auto">
                            <table id="datatable" class="table table-sm"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">No</th>
                                        <th class="whitespace-nowrap">Kode Guru</th>
                                        <th class="whitespace-nowrap">Nama Guru</th>
                                        <th class="whitespace-nowrap">Alamat</th>
                                        <th class="whitespace-nowrap">No.HP</th>
                                        <th class="whitespace-nowrap">Akun</th>
                                        @if (Auth::user()->role == '1')
                                            <th class="whitespace-nowrap">Action</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lecturers as $key => $lecturer)
                                        <tr>
                                            <td align="center">
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $lecturer->code_lecturers }}
                                            </td>
                                            <td>
                                                {{ $lecturer->name }}
                                            </td>
                                            <td>
                                                {{ $lecturer->alamat }}
                                            </td>
                                            <td>
                                                {{ $lecturer->no_hp }}
                                            </td>
                                            <td>
                                                {{ $lecturer['users']['email'] }}
                                            </td>
                                            @if (Auth::user()->role == '1')
                                                <td class="">
                                                    <a id="delete"
                                                        href="{{ route('admin.lecturer.delete', $lecturer->id) }}"
                                                        class="btn btn-danger mr-1 mb-2">
                                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                                    </a>
                                                    <a href="{{ route('admin.lecturer.edit', $lecturer->id) }}"
                                                        class="btn btn-primary mr-1 mb-2">
                                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@stop
