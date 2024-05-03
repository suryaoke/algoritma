{!! Form::hidden('idlecturer', isset($lecturers->id) ? $lecturers->id : '', [
    'class' => 'form-control',
    'id' => 'idlecturer',
]) !!}

<div class="mt-3">
    <label>Kode Guru</label>
    {!! Form::text('code_lecturers', null, [
        'class' => 'intro-x login__input form-control py-3 px-4 block',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Kode Guru',
    ]) !!}
</div>

<div class="mt-3">
    <label>Nama</label>
    {!! Form::text('name', null, [
        'class' => 'intro-x login__input form-control py-3 px-4 block ',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Nama Guru',
    ]) !!}
</div>
<div class="mt-3">
    <label>Alamat</label>
    {!! Form::text('alamat', null, [
        'class' => 'intro-x login__input form-control py-3 px-4 block ',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Alamat',
    ]) !!}
</div>

<div class="mt-3">
    <label>No.HP</label>
    {!! Form::text('no_hp', null, [
        'class' => 'intro-x login__input form-control py-3 px-4 block ',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan No HP',
    ]) !!}
</div>


<div class="mt-3">
    <label>Akun</label>
    {!! Form::select('akun', $users, isset($lecturers->akun) ? $lecturers->akun : '', [
        'class' => 'form-control select2 to-select',
        'id' => 'courses',
        'required',
        'placeholder' => 'Pilih User Email',
    ]) !!} <label id="courses-error" class="error" for="courses" style="display: none;">This field is
        required.</label>
</div>


<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.lecturers') }}"
        class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
