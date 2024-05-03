@extends('admin.layouts.master')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="page-content">
        <h1 class="text-lg font-medium mb-4">Waktu Berhalangan</h1>
        <div class="mb-3 intro-y flex flex-col sm:flex-row items-center mt-4">
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a href="{{ route('admin.timenotavailable.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Data</a>
            </div>
        
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
                                        <th class="whitespace-nowrap">Hari</th>
                                        <th class="whitespace-nowrap">Waktu</th>
                                        <th class="whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timenotavailables as $key => $timenotavailable)
                                        <tr>
                                            <td align="center">
                                                {{ $key + 1 }}
                                            </td>
                                            <td>{{ $timenotavailable->guru->name ?? '' }}</td>
                                            <td>{{ $timenotavailable->day->name_day ?? '' }}</td>
                                            <td>{{ $timenotavailable->time->range ?? '' }}</td>
                                            <td>
                                                <a id="delete"
                                                    href="{{ route('admin.timenotavailable.delete', $timenotavailable->id) }}"
                                                    class="btn btn-danger mr-1 mb-2">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </a>
                                                <a href="{{ route('admin.timenotavailable.edit', $timenotavailable->id) }}"
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
