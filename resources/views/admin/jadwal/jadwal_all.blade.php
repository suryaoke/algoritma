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
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }} mb-4">
            {{ session('message') }}
        </div>
    @endif
    {{--  // Bagian search //  --}}
    <h1 class="text-lg font-medium mb-4 mt-4">Jadwal Mata Pelajaran All</h1>

    <div class="mb-4 intro-y flex flex-col sm:flex-row items-center mt-4">

        <form role="form" action="{{ route('jadwal.all') }}" method="get" class="sm:flex">
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


    <div class="col-span-2 mb-4 mt-4">
        @if (Auth::user()->role == '3')
            <a class="btn btn-primary btn-block" data-tw-toggle="modal" data-tw-target="#button-modal-preview">
                <span class="glyphicon glyphicon-download"></span> <i data-lucide="send" class="w-4 h-4"></i>&nbsp;Kirim
                Jadwal All
            </a>
        @endif
        <a class="btn btn-success btn-block" href=" {{ route('test.excel') }} ">
            <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                class="w-4 h-4"></i>&nbsp;Export Excel
        </a>
        {{--  <a class="btn btn-success btn-block" href=" {{ route('jadwal.excel') }} ">
            <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                class="w-4 h-4"></i>&nbsp;Export Excel
        </a>  --}}
        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#pdf-modal-preview" class="btn btn-warning"> <span
                class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                class="w-4 h-4"></i>&nbsp;Export Pdf</a>
        @if (Auth::user()->role == '3')
            <a class="btn btn-outline-secondary btn-block ml-2" data-tw-toggle="modal"
                data-tw-target="#tambah-schedule-modal">
                <span class="glyphicon glyphicon-download"></span> Tambah Jadwal
            </a>
        @endif

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
                                    <th style="text-align:center;">No. </th>
                                    <th style="text-align:center;">Hari</th>
                                    <th style="text-align:center;">Jam</th>
                                    <th style="text-align:center;">Nama Ruangan</th>
                                    <th style="text-align:center;">Mata Pelajaran</th>
                                    <th style="text-align:center;">Guru</th>
                                    <th style="text-align:center;">Semester</th>
                                    <th style="text-align:center;">JP</th>
                                    <th style="text-align:center;">Kelas</th>
                                    @if (Auth::user()->role == '3')
                                        <th style="text-align:center;">Status</th>

                                        <th style="text-align:center;">Action</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $lastEndTime = null; // Inisialisasi waktu terakhir
                                @endphp
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td align="center">{{ $key + 1 }} </td>

                                        <td>{{ isset($schedule->day->name_day) ? $schedule->day->name_day : '' }} 
                                           </td>
                                        {{--  <td>{{ isset($schedule->time->range) ? $schedule->time->range : '' }}</td>  --}}
                                        <td>
                                            @php
                                                $originalValue = isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : 0;
                                                $calculatedValue = $originalValue * 40;

                                                // Hitung jam dan menit berdasarkan nilai yang dihitung
                                                $hours = floor($calculatedValue / 60);
                                                $minutes = $calculatedValue % 60;

                                                // Set waktu mulai berdasarkan hari
                                                if ($lastEndTime && $schedule->days_id == $lastDaysId) {
                                                    $startTime = Carbon\Carbon::parse($lastEndTime);
                                                } else {
                                                    // Ubah waktu mulai berdasarkan nilai name_day
                                                    if (isset($schedule->day->name_day)) {
                                                        if ($schedule->day->name_day == 'Senin' || $schedule->day->name_day == 'Jumat') {
                                                            $startTime = Carbon\Carbon::createFromTime(8, 0);
                                                        } else {
                                                            $startTime = Carbon\Carbon::createFromTime(7, 0);
                                                        }
                                                    } else {
                                                        $startTime = Carbon\Carbon::createFromTime(7, 0);
                                                    }
                                                }

                                                // Hitung waktu selesai

                                                $endTime = $startTime
                                                    ->copy()
                                                    ->addHours($hours)
                                                    ->addMinutes($minutes);

                                                // Cek apakah ini iterasi terakhir untuk days_id tertentu
                                                if ($key === count($schedules) - 1 || $schedule->days_id !== $schedules[$key + 1]->days_id) {
                                                    // Mengeset waktu selesai menjadi pukul 15:30 hanya untuk course terakhir pada setiap days_id
                                                    $endTime = Carbon\Carbon::createFromTime(15, 30);
                                                }

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
                                        @if (Auth::user()->role == '3')
                                            <td>
                                                @if ($schedule->status == '0')
                                                    <span class="btn btn-outline-warning">Proses Penjadwalan</span>
                                                @elseif($schedule->status == '1')
                                                    <span class="btn btn-outline-pending">Menunggu Verifikasi</span>
                                                @elseif($schedule->status == '2')
                                                    <span class="btn btn-outline-success">Kirim</span>
                                                @elseif($schedule->status == '3')
                                                    <span class="btn btn-outline-danger">Ditolak</span>
                                                @endif

                                            </td>

                                            <td>
                                                <a id="delete" href="{{ route('jadwal.delete', $schedule->id) }}"
                                                    class="btn btn-danger mr-1 mb-2">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </a>

                                                <a href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#ubah-schedule-modal-{{ $schedule->id }}"
                                                    class="btn btn-primary mb-2">
                                                    <i data-lucide="edit" class="w-4 h-4 mb"></i>
                                                </a>

                                                @if ($schedule->status == '0' || $schedule->status == '3')
                                                    <a href="javascript:;" data-tw-toggle="modal"
                                                        data-tw-target="#kirim-schedule-modal-{{ $schedule->id }}"
                                                        class="btn btn-primary">
                                                        <i data-lucide="send" class="w-4 h-4"></i>
                                                    </a>
                                                @endif

                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- BEGIN: Modal Kirim Jadwal All-->
    <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('jadwal.status') }}">
                @csrf
                <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i> </a>
                    <div class="modal-body p-0">
                        <div class="p-5 text-center"> <i data-lucide="check-circle"
                                class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Kirim Jadwal </div>
                            <div class="text-slate-500 mt-2">Data Jadwal Mata Pelajaran Di Kirim Ke Kepsek..!!</div>
                        </div>
                        <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                class="btn btn-primary w-24">Ok</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END: Modal Content -->


    <!-- BEGIN: Modal Kirim Jadwal Satu-->
    @foreach ($schedules as $schedule)
        <div id="kirim-schedule-modal-{{ $schedule->id }}" class="modal" tabindex="-1" aria-hidden="true"
            aria-labelledby="kirim-schedule-modal-label-{{ $schedule->id }}">
            <div class="modal-dialog">

                <form method="post" action="{{ route('jadwal.status.one', $schedule->id) }}">
                    @csrf
                    <input type="hidden" value="1" name="status">
                    <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i> </a>
                        <div class="modal-body p-0">
                            <div class="p-5 text-center"> <i data-lucide="check-circle"
                                    class="w-16 h-16 text-success mx-auto mt-3"></i>
                                <div class="text-3xl mt-5">Kirim Jadwal </div>
                                <div class="text-slate-500 mt-2">Data Jadwal Mata Pelajaran Di Kirim Ke Kepsek..!!
                                </div>
                            </div>
                            <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                    class="btn btn-primary w-24">Ok</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- END: Modal Content -->
    @endforeach

    <!-- BEGIN: Modal Kirim Jadwal Satu-->

    



    <div id="pdf-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                        class="w-8 h-8 text-slate-400"></i> </a>
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Export Pdf Jadwal Mapel</h2>
                    <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"
                            aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal"
                                class="w-5 h-5 text-slate-500"></i> </a>
                        <div class="dropdown-menu w-40">

                        </div>
                    </div>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->

                <form method="post" action="{{ route('jadwalcustom.pdf') }}">
                    @csrf
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-6"> <label for="edit-jam">Kelas </label>
                            <select name="kelas" id="gurus_id" class="form-control w-full" required>
                                <option value="">Pilih Kelas</option>
                                @php
                                    $addedClassRooms = [];
                                @endphp
                                @foreach ($schedules as $key => $schedule)
                                    @if (!in_array($schedule->teach->class_room, $addedClassRooms))
                                        <option value="{{ $schedule->teach->class_room }}">
                                            {{ $schedule->teach->class_room }}</option>
                                        @php
                                            $addedClassRooms[] = $schedule->teach->class_room;
                                        @endphp
                                    @endif
                                @endforeach
                            </select>

                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="edit-jam">Semester </label>
                            <select name="semester" id="edit-jam" class="form-control w-full" required>
                                <option value="">Pilih Semester</option>
                                @php
                                    $addedSemesters = [];
                                @endphp
                                @foreach ($schedules->unique('teach.course.semester') as $schedule)
                                    @if (!in_array($schedule->teach->course->semester, $addedSemesters))
                                        <option value="{{ $schedule->teach->course->semester }}">
                                            {{ $schedule->teach->course->semester }}
                                        </option>
                                        @php
                                            $addedSemesters[] = $schedule->teach->course->semester;
                                        @endphp
                                    @endif
                                @endforeach
                            </select>

                        </div>

                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-20">Custom</button>
                </form>
                <a href=" {{ route('jadwal.pdf') }}" type="button" data-tw-dismiss="modal"
                    class="btn btn-outline-secondary w-20 mr-1">All</a>


            </div>
            </form>

        </div>
    </div>
    </div>













    <!-- Masukkan jQuery sebelum kode JavaScript Anda -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Kode JavaScript Anda -->
    <script type="text/javascript">
        $('#myAction').change(function() {
            var action = $(this).val();
            window.location = action;
        });
    </script>






    <!-- Modal Tambah Jadwal mapel -->

    <div id="tambah-schedule-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Tambah Jadwal Mapel</h2>
                </div>
                <form method="post" action="{{ route('jadwalmapel.store') }}" enctype="multipart/form-data"
                    id="myForm1">
                    @csrf
                    <div class="modal-body">
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mb-4">
                            <!-- Kode Pengampu -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-2">
                                    <div class="mb-2">
                                        <label for="teachs_id">PENGAMPU</label>
                                    </div>
                                    <select name="teachs_id" id="teachs_id" class="tom-select w-full" required>
                                        <optgroup>
                                            <option value="">Pilih Kode Pengampu</option>
                                            @foreach ($pengampu as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->gurus->name }} / {{ $item->class_room }} /
                                                    {{ $item->course->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <!-- Tabel Data -->
                            <div class="col-span-12">
                                <div class="card overflow-x-auto">
                                    <div class="card-body table-responsive">
                                        <table id="data-table" class="table table-sm" style="width: 100%;">
                                            <thead>
                                                <tr>

                                                    <th>Nama Guru</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Kelas</th>
                                                    <th>Jp</th>
                                                    <th>Kurikulum</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mt-8 mb-4">
                            <!-- Waktu -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-2">
                                    <label for="edit-jam">Waktu</label>
                                </div>
                                <select name="times_id" id="times_id" class="form-control w-full" required>
                                    <option value="">Pilih Waktu</option>
                                    @foreach ($time as $item)
                                        <option value="{{ $item->id }}">{{ $item->range }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Hari -->
                            <div class="col-span-12 sm:col-span-4">
                                <label for="modal-form-4" class="form-label">Hari</label>
                                <select name="days_id" id="days_id" class="form-control w-full" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach ($day as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ruangan -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-2">
                                    <label for="edit-ruangan">Ruangan</label>
                                </div>
                                <select name="rooms_id" id="rooms_id" class="form-control w-full" required>
                                    <option value="">Pilih Ruangan</option>
                                    @foreach ($room as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <p class="horizontal-align ml-4">
                        <i data-lucide="alert-triangle" class="mr-1 text-danger"></i>
                        <span class="text-danger">Pastikan data yang diinputkan benar.</span>
                    </p>

                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm1').validate({
                rules: {
                    times_id: {
                        required: true,
                    },
                    days_id: {
                        required: true,
                    },
                    rooms_id: {
                        required: true,
                    },

                },
                messages: {
                    times_id: {
                        required: 'Please Enter Your Waktu',
                    },
                    days_id: {
                        required: 'Please Enter Your Hari',
                    },
                    rooms_id: {
                        required: 'Please Enter Your Ruangan',
                    },


                },
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>

    {{--  Scrip Menampilkan data tabel  --}}

    <script>
        // Tangkap elemen dropdown select
        const selectElement = document.getElementById('teachs_id');

        // Tambahkan event listener untuk menangani perubahan dalam dropdown
        selectElement.addEventListener('change', function() {
            // Ambil nilai yang dipilih dalam dropdown
            const selectedValue = selectElement.value;

            // Buat AJAX request atau manipulasi data sesuai kebutuhan Anda
            // Di sini, kita hanya akan menambahkan data ke dalam tabel sebagai contoh
            const tableBody = document.querySelector('#data-table tbody');
            tableBody.innerHTML = ''; // Bersihkan isi tabel sebelum menambahkan data baru

            // Loop melalui data pengampu untuk menemukan yang sesuai dengan nilai yang dipilih
            @foreach ($pengampu as $item)
                if ("{{ $item->id }}" === selectedValue) {
                    const newRow = tableBody.insertRow();
                    const cell1 = newRow.insertCell(0); // Hanya satu kolom yang perlu ditambahkan sekarang
                    const cell2 = newRow.insertCell(1);
                    const cell3 = newRow.insertCell(2);
                    const cell4 = newRow.insertCell(3);
                    const cell5 = newRow.insertCell(4);


                    cell1.textContent = "{{ $item->gurus->name }}"; // Nama Guru (berdasarkan relasi)
                    cell2.textContent = "{{ $item->course->name }}"; // Mata Pelajaran (berdasarkan relasi)
                    cell3.textContent = "{{ $item->class_room }}";
                    cell4.textContent = "{{ $item->course->jp }}"; // Jp
                    cell5.textContent = "{{ $item->year }}"; // Kurikulum






                }
            @endforeach
        });
    </script>

    <!-- End Modal Tambah Jadwal mapel -->






    <!-- Modal Edit Jadwal mapel -->

    @foreach ($schedules as $item)
        <div class="modal fade" id="ubah-schedule-modal-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ubah-schedule-modal-label-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Edit Jadwal Mapel</h2>
                    </div>
                    <form method="post" action="{{ route('jadwalmapel.update', $item->id) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="grid grid-cols-12 gap-4 gap-y-3 mb-4">

                                <!-- Tabel Data -->
                                <div class="col-span-12">
                                    <div class="card overflow-x-auto">
                                        <div class="card-body table-responsive">
                                            <table id="data-table1" class="table table-sm" style="width: 100%;">
                                                <thead>
                                                    <tr>

                                                        <th>Nama Guru</th>
                                                        <th>Mata Pelajaran</th>
                                                        <th>Kelas</th>
                                                        <th>Kurikulum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $pengampuid = App\Models\Teach::find($item->teachs_id);
                                                        $mapelid = App\Models\Course::find($pengampuid->courses_id);
                                                        $guruid = App\Models\Guru::find($pengampuid->gurus_id);
                                                    @endphp

                                                    <tr>

                                                        <td> {{ $guruid->name }} </td>
                                                        <td> {{ $mapelid->name }} </td>
                                                        <td> {{ $pengampuid->class_room }} </td>
                                                        <td> {{ $pengampuid->year }} </td>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="grid grid-cols-12 gap-4 gap-y-3 mt-8 mb-4">
                                <!-- Waktu -->
                               
                                    <select name="times_id" id="times_id" class="form-control w-full" required hidden>
                                        <option value="{{ $item->times_id }}">{{ $item['waktus']['range'] }}</option>
                                      
                                    </select>
                               

                                <!-- Hari -->
                                <div class="col-span-12 sm:col-span-4">
                                    <label for="modal-form-4" class="form-label">Hari</label>
                                    <select name="days_id" id="days_id" class="form-control w-full" required>
                                        <option value="{{ $item->days_id }}">{{ $item['haris']['name_day'] }}</option>
                                        @foreach ($day as $item2)
                                            <option value="{{ $item2->id }}">{{ $item2->name_day }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Ruangan -->
                                <div class="col-span-12 sm:col-span-4">
                                    <div class="mb-2">
                                        <label for="edit-ruangan">Ruangan</label>
                                    </div>
                                    <select name="rooms_id" id="rooms_id" class="form-control w-full" required>
                                        <option value="{{ $item->rooms_id }}">{{ $item['ruangans']['name'] }}</option>
                                        @foreach ($room as $item3)
                                            <option value="{{ $item3->id }}">{{ $item3->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p class="horizontal-align ml-4">
                            <i data-lucide="alert-triangle" class="mr-1 text-danger"></i>
                            <span class="text-danger">Pastikan data yang diinputkan benar.</span>
                        </p>

                        <div class="modal-footer">
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-primary w-20">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <!-- End Modal Edit Jadwal mapel-->

@stop
