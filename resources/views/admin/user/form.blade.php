{!! Form::hidden('iduser', isset($users->id) ? $users->id : '', ['class' => 'form-control', 'id' => 'iduser']) !!}
<div class="mt-3">
    <input type="hidden" name="status" value="1">
    <label>
        Nama Lengkap
    </label>
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Nama Lengkap',
    ]) !!}
</div>
<div class="mt-3">
    <label>
        Email
    </label>
    {!! Form::email('emailuser', isset($users->email) ? $users->email : '', [
        'class' => 'form-control',
        'required',
        'maxlength' => '50',
        'placeholder' => 'Masukkan Email',
        'id' => 'emailuser',
    ]) !!}
</div>
<div class="mt-3">
    <label>
        Role
    </label>
    {!! Form::select(
        'role',
        [
            '1' => 'Admin',
            '2' => 'Kepala Sekolah',
            '3' => 'Waka Kurikulum',
            '4' => 'Jurusan',
            '5' => 'Guru',
        ],
        null,
        [
            'class' => 'intro-x login__input form-control py-3 px-4 blockselect2 to-select',
            'id' => 'role',
            'required',
            'placeholder' => 'Pilih Role',
        ],
    ) !!}
    <label id="jp-error" class="error" for="jp" style="display: none;">This field is required.</label>
</div>

@if (empty($users->password))
    <div class="mt-3">
        <label>
            Password
        </label>
        {!! Form::password('password', [
            'class' => 'form-control',
            'id' => 'password',
            'maxlength' => '20',
            'required',
            'placeholder' => 'Masukkan Password',
        ]) !!}
    </div>
    <div class="mt-3">
        <label>
            Password Konfirmasi
        </label>
        {!! Form::password('password_confirmation', [
            'class' => 'form-control',
            'maxlength' => '20',
            'required',
            'placeholder' => 'Masukkan assword Konfirmasi',
        ]) !!}
    </div>
@else
    <div class="mt-3">
        <label>
            Password
        </label>
        {!! Form::password('password', [
            'class' => 'form-control',
            'id' => 'password',
            'maxlength' => '20',
            'placeholder' => 'Masukkan Password',
        ]) !!}
    </div>
    <div class="mt-3">
        <label>
            Password Konfirmasi
        </label>
        {!! Form::password('password_confirmation', [
            'class' => 'form-control',
            'maxlength' => '20',
            'placeholder' => 'Masukkan Password Konfirmasi',
        ]) !!}
    </div>
@endif
<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.user') }}" class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
