@extends('admin.layouts.master')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> <!-- Include jQuery Validation plugin -->
@section('content')
    <div class="intro-y flex items-center mt-8 mb-4">
        <h1 class="text-lg font-medium mr-auto">Generate Jadwal Mata Pelajaran</h1>
    </div>
    <div class="box-body">
        <div class="row">
            <form role="form" action="{{ route('admin.generates.submit') }}" method="get" id="form-register">

                <div class="grid grid-cols-12 gap-2 mt-4">
                    <div class="form-control col-span-4">
                        <label for="year">Tahun Ajaran</label>
                        <select name="year" class="form-control select2 to-select required mt-2" id="year"
                            placeholder="Pilih Tahun Ajaran">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($years as $key => $value)
                                <option value="{{ $key }}" {{ request('year') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-control col-span-4">

                        <label for="semester">Semester</label>
                        <select name="semester" class="form-control select2 to-select required mt-2" id="semester"
                            placeholder="Pilih Semester">
                            <option value="">Pilih Semester</option>
                            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil
                            </option>
                        </select>

                    </div>

                    <div class="form-control col-span-4">
                        {{--  <label for="kromosom">Nilai Pembangkitan Kromosom</label>  --}}
                        {{--  <input type="hidden" name="kromosom" id="kromosom" value="3">  --}}


                    </div>

                </div>

                <div class="grid grid-cols-12 gap-2 mt-4">
                    <div class="form-control col-span-4">
                        {{--  <label for="generasi">Nilai Maksimal Generasi</label>  --}}
                        {{--  <input type="hidden" name="generasi" id="generasi" value="2">  --}}


                    </div>

                    <div class="form-control col-span-4">
                        {{--  <label for="crossover">Nilai Crossover</label>  --}}
                        {{--  <input type="hidden" name="crossover" id="crossover" value="0.70">  --}}

                    </div>

                    <div class="form-control col-span-4">
                        {{--  <label for="mutasi">Nilai Mutasi</label>  --}}
                        {{--  <input type="hidden" name="mutasi" id="mutasi" value="0.40">
                          --}}
                    </div>
                </div>
                <div class="mt-8">
                    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top mb-2 xl:mb-0"
                        type="submit">Generate</button>
                    <a href="{{ route('admin.gurus') }}"
                        class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#form-register').validate({
                rules: {
                    semester: {
                        required: true,
                    },
                    kromosom: { // Ubah dari "kormosom" ke "kromosom"
                        required: true,
                    },
                    year: {
                        required: true,
                        digits: true,
                    },
                    generasi: {
                        required: true,
                        digits: true, // Menambahkan validasi untuk angka saja
                    },
                    mutasi: {
                        required: true,
                    },
                    crossover: {
                        required: true,
                    },
                },
                messages: {
                    semester: {
                        required: 'Please Enter Your Semester',
                    },
                    kromosom: { // Ubah dari "kormosom" ke "kromosom"
                        required: 'Please Enter Your kromosom',
                    },
                    year: {
                        required: 'Please Enter Your Tahun',
                        digits: 'Please enter only numbers',
                    },
                    generasi: {
                        required: 'Please Enter Your Generasi',
                        digits: 'Please enter only numbers', // Menambahkan pesan untuk angka saja
                    },
                    mutasi: {
                        required: 'Please Enter Your Mutasi',
                    },
                    crossover: {
                        required: 'Please Enter Your Crossover',
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
@endsection
