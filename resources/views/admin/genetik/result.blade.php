@extends('admin.layouts.master')

@section('title', 'Hasil Generate Algoritma')

@section('style')
    <style type="text/css">
        .panel-body {
            width: auto;
            height: auto;
            overflow: auto;
        }
    </style>
@stop

@section('content')
    @if (session('notification'))
        <div class="alert alert-success">
            {!! session('notification') !!}
        </div>
    @endif
    <div class="row">
        <div class="grid grid-cols-12 gap-2 mt-4 mb-4">
            <div class="mr-2"> <!-- Tombol Kembali -->
                <a class="btn btn-warning btn-block" href="{{ route('admin.generates', request()->all()) }}">
                    <span class="glyphicon glyphicon-hand-left"></span> <i data-lucide="skip-back" class="w-4 h-4"></i>
                    Back
                </a>
            </div>
            <div class="col-span-2"> <!-- Tombol Export Excel -->
                {{--  <a class="btn btn-primary btn-block" href="{{ route('admin.generates.excel', $id) }}">
                    <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                        class="w-4 h-4"></i>&nbsp;Export Excel
                </a>  --}}
                <a class="btn btn-primary btn-block" href="{{ route('test1.excel', $id) }}">
                    <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                        class="w-4 h-4"></i>&nbsp;Export Excel
                </a>
            </div>
            <div class="col-span-4 "> <!-- Dropdown -->
                @if (!empty($data_kromosom))
                    <select class="form-control select2" id="myAction">
                        @foreach ($data_kromosom as $key => $kromosom)
                            <option value="{{ $key + 1 }}"
                                @if ($id == $key + 1) selected="selected" @endif>
                                @if ($kromosom['value'] == 1)
                                    Jadwal Terbaik Ke {{ $key + 1 }}
                                @else
                                    Jadwal ke {{ $key + 1 }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-span-2  "> <!-- Tombol Export Excel -->
                <a class="btn btn-success btn-block" data-tw-toggle="modal" data-tw-target="#button-modal-preview">
                    <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="save"
                        class="w-4 h-4"></i>&nbsp;Simpan Data
                </a>
            </div>
        </div>
    </div>
    {{--  // Bagian search //  --}}
    <div class="mb-4 intro-y flex flex-col sm:flex-row items-center mt-8">

        <form role="form" action="{{ route('admin.generates.result', ['id' => $id]) }}" method="get" class="sm:flex">
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchdays" class="form-control" placeholder="Hari"
                        value="{{ request('searchdays') }}">
                </div>
            </div>
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
    {{--  // End Bagian search //  --}}


    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="overflow-x-auto">
                        <table id="datatable" class="table table-sm"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Hari</th>
                                    <th style="text-align:center;">Jam</th>
                                    <th style="text-align:center;">Nama Ruangan</th>
                                    <th style="text-align:center;">Mata Pelajaran</th>
                                    <th style="text-align:center;">Guru</th>
                                    <th style="text-align:center;">Semester</th>
                                    <th style="text-align:center;">JP</th>
                                    <th style="text-align:center;">Kelas</th>
                                    {{--  <th style="text-align:center;">Action</th>  --}}

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $lastEndTime = null; // Inisialisasi waktu terakhir
                                @endphp
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td align="center">{{ $key + 1 }}</td>
                                        <td>{{ isset($schedule->day->name_day) ? $schedule->day->name_day : '' }}</td>
                                        <td>
                                            @php
                                                $originalValue = isset($schedule->teach->course->jp)
                                                    ? $schedule->teach->course->jp
                                                    : 0;
                                                $calculatedValue = $originalValue * 40;

                                                // Hitung jam dan menit berdasarkan nilai yang dihitung
                                                $hours = floor($calculatedValue / 60);
                                                $minutes = $calculatedValue % 60;

                                                // Format rentang waktu
                                                if ($lastEndTime && $schedule->days_id == $lastDaysId) {
                                                    // Gunakan waktu terakhir sebagai waktu mulai
                                                    $startTime = Carbon\Carbon::parse($lastEndTime);
                                                } else {
                                                    // Gunakan waktu standar 07:00 sebagai waktu mulai jika days_id berbeda
                                                    $startTime = Carbon\Carbon::createFromTime(7, 0);
                                                }

                                                // Hitung waktu selesai
                                                $endTime = $startTime->copy()->addHours($hours)->addMinutes($minutes);

                                                echo $startTime->format('H:i') . ' - ' . $endTime->format('H:i');

                                                // Simpan waktu selesai untuk digunakan pada iterasi selanjutnya
                                                $lastEndTime = $endTime->format('H:i');
                                                $lastDaysId = $schedule->days_id;
                                            @endphp
                                        </td>
                                        <td>
                                            {{ isset($schedule->room->name) ? $schedule->room->name : '' }} :
                                            {{ isset($schedule->teach->class_room) ? $schedule->teach->class_room : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->course->name) ? $schedule->teach->course->name : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->guru->name) ? $schedule->teach->guru->name : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->course->semester) ? $schedule->teach->course->semester : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->class_room) ? $schedule->teach->class_room : '' }}
                                        </td>
                                        {{--  <td>
                                            <a id="delete" href="{{ route('generate.delete', $schedule->id) }}"
                                                class="btn btn-danger mr-1 mb-2">
                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                            </a>
                                            <a href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#edit-schedule-modal-{{ $schedule->id }}"
                                                class="btn btn-primary">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        </td>  --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jadwal -->
    @foreach ($schedules as $schedule)
        <div class="modal fade" id="edit-schedule-modal-{{ $schedule->id }}" tabindex="-1" role="dialog"
            aria-labelledby="edit-schedule-modal-label-{{ $schedule->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-schedule-modal-label-{{ $schedule->id }}">
                            Edit Jadwal
                            {{ $schedule->teach->guru->name }}
                        </h5>

                    </div>
                    <div class="modal-body">
                        <!-- Isi form edit jadwal di sini -->

                        <form method="post" action="{{ route('generate.update', $schedule->id) }}">
                            @csrf
                            <!-- Field dan input form untuk mengedit data jadwal -->
                            <div class="form-group">
                                <label for="edit-hari">Hari</label>
                                <select name="days_id" id="edit-hari" class="form-control w-full" required>
                                    <option value="{{ $schedule->day->id }}">{{ $schedule->day->name_day }}</option>
                                    @foreach ($day as $days)
                                        <option value="{{ $days->id }}">{{ $days->name_day }}</option>
                                    @endforeach

                                </select>
                            </div>


                            <select name="times_id" id="edit-jam" class="form-control w-full" required hidden>
                                <option value="{{ $schedule->time->id }}">{{ $schedule->time->range }}</option>

                            </select>

                            <div class="form-group mt-2">
                                <label for="edit-ruangan">Ruangan</label>
                                <select name="rooms_id" id="edit-ruangan" class="form-control w-full" required>
                                    <option value="{{ $schedule->room->id }}"> {{ $schedule->room->name }}
                                    </option>
                                    @foreach ($room as $rooms)
                                        <option value="{{ $rooms->id }}">{{ $rooms->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4"> <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-20 mr-1">Cancel
                                </button>
                                <button type="submit" class="btn btn-primary w-20">Save</button>
                            </div>
                            <!-- END: Modal Footer -->
                        </form>

                        <!-- Akhir form edit jadwal -->
                    </div>
                </div>
            </div>
        </div>
    @endforeach






    <!-- BEGIN: Modal Simpan Jadwal -->

    <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('generate.save', ['id' => $id]) }}">
                @csrf
                <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i> </a>
                    <div class="modal-body p-0">
                        <div class="p-5 text-center"> <i data-lucide="check-circle"
                                class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Simpan Jadwal </div>
                            <div class="text-slate-500 mt-2">Data yang telah disimpan sebelumnya akan terhapus..!!</div>
                        </div>
                        <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                class="btn btn-primary w-24">Ok</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END: Modal Content -->









    <!-- Masukkan jQuery sebelum kode JavaScript Anda -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Kode JavaScript Anda -->
    <script type="text/javascript">
        $('#myAction').change(function() {
            var action = $(this).val();
            window.location = action;
        });
    </script>



@stop
