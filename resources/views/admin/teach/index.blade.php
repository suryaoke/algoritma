@extends('admin.layouts.master')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="page-content">
        <h1 class="text-lg font-medium mb-4">Data Pengampu All</h1>
        <div class="mb-3 intro-y flex flex-col sm:flex-row items-center mt-4">
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a href="{{ route('admin.teach.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Data</a>
            </div>
            <form role="form" action="{{ route('admin.teachs') }}" method="get" class="sm:flex">
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchgurus" class="form-control" placeholder="Nama Guru"
                            value="{{ request('searchgurus') }}">
                    </div>
                </div>
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchcourse" class="form-control" placeholder="Mata Pelajaran"
                            value="{{ request('searchcourse') }}">
                    </div>
                </div>
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchclass" class="form-control" placeholder="Kelas"
                            value="{{ request('searchclass') }}">
                    </div>
                </div>
                <div class="sm:ml-1">
                    <button type="submit" class="btn btn-default">Search</button>
                </div>
            </form>
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
                                        <th class="whitespace-nowrap">No.</th>
                                        <th class="whitespace-nowrap">Nama Guru</th>
                                        <th class="whitespace-nowrap">Mata Pelajaran</th>
                                        <th class="whitespace-nowrap">Kelas</th>
                                        <th class="whitespace-nowrap">Tahun Ajaran</th>
                                        <th class="whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachs as $key => $teach)
                                        <tr>
                                            <td align="center">
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ optional($teach->guru)->code_gurus }}
                                            </td>
                                            <td>
                                                {{ optional($teach->course)->name }}
                                            </td>
                                            <td>
                                                {{ $teach->class_room }}
                                            </td>
                                            <td>
                                                {{ $teach->year }}
                                            </td>
                                            <td>
                                                <a id="delete" href="{{ route('admin.teach.delete', $teach->id) }}"
                                                    class="btn btn-danger mr-1 mb-2">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </a>
                                                <a href="{{ route('admin.teach.edit', $teach->id) }}"
                                                    class="btn btn-success mr-1 mb-2">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>
                                            </td>
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
