    {!! Form::hidden('idcourse', isset($courses->id) ? $courses->id : '', [
        'class' => 'intro-x login__input form-control py-3 px-4 block ',
        'id' => 'idcourse',
    ]) !!}
    <div class="mt-3">
        <label>
            Kode Mata Pelajaran
        </label>
        {!! Form::text('code_courses', null, [
            'class' => 'intro-x login__input form-control py-3 px-4 block ',
            'required',
            'maxlength' => '100',
            'placeholder' => 'Masukkan Kode Mata Kuliah',
            'id' => 'code_courses',
        ]) !!}
    </div>
    <div class="mt-3">
        <label>
            Nama Mata Pelajaran
        </label>
        {!! Form::text('namecourses', isset($courses->name) ? $courses->name : '', [
            'class' => 'intro-x login__input form-control py-3 px-4 block ',
            'required',
            'maxlength' => '100',
            'placeholder' => 'Masukkan Nama Mata Kuliah',
            'id' => 'namecourses',
        ]) !!}
    </div>
    <div class="mt-3">
        <label>
            Jp
        </label>
        <input type="text" id="jp" name="jp" class="form-control" required maxlength="100"
    placeholder="Jumlah JP">
        <label id="jp-error" class="error" for="jp" style="display: none;">This field is required.</label>
    </div>
    <div class="mt-3">
        <label>
            Semester
        </label>
        {!! Form::select(
            'semester',
            [
                'Ganjil' => 'Ganjil',
                'Genap' => 'Genap',
            ],
            null,
            [
                'class' => 'intro-x login__input form-control py-3 px-4 block select2 to-select',
                'id' => 'semester',
                'required',
                'placeholder' => 'Pilih Semester',
            ],
        ) !!}
        <label id="semester-error" class="error" for="semester" style="display: none;">This field is required.</label>
    </div>

    <div class="mt-3">
        <label>Jurusan </label>
        {!! Form::select('jurusan', $jurusan, isset($courses->name) ? $courses->name : '', [
            'class' => 'form-control select2 to-select',
            'id' => 'jurusan',
            'required',
            'placeholder' => 'Pilih Jurusan',
        ]) !!} <label id="courses-error" class="error" for="courses" style="display: none;">This
            field is
            required.</label>
    </div>
    <div class="mt-3">
        <label>
            Jenis
        </label>
        {!! Form::select('type', $type, null, [
            'class' => 'intro-x login__input form-control py-3 px-4 block select2 to-select',
            'id' => 'type',
            'required',
            'placeholder' => 'Pilih Jenis',
        ]) !!}
        <label id="type-error" class="error" for="type" style="display: none;">This field is required.</label>
    </div>
    <div class="mt-4">
        <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
        <a href="{{ route('admin.courses') }}"
            class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
    </div>
