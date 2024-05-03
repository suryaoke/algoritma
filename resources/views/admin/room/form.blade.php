{!! Form::hidden('idroom', isset($rooms->id) ? $rooms->id : '', ['class' => 'form-control', 'id' => 'idroom']) !!}
<div class="mt-3">
    <label>
        Kode Ruangan
    </label>
    {!! Form::text('code_rooms', null, [
        'class' => 'form-control',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Kode Ruang',
        'id' => 'code_rooms',
    ]) !!}
</div>
<div class="mt-3">
    <label>
        Nama Ruangan
    </label>
    {!! Form::text('namerooms', isset($rooms->name) ? $rooms->name : '', [
        'class' => 'form-control',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Nama Ruangan',
        'id' => 'namerooms',
    ]) !!}
</div>


<div class="mt-3">
    <label>Jurusan </label>
    {!! Form::select('jurusan', $jurusan, isset($rooms->name) ? $rooms->name : '', [
        'class' => 'form-control select2 to-select',
        'id' => 'jurusan',
        'required',
        'placeholder' => 'Pilih Jurusan',
    ]) !!} <label id="courses-error" class="error" for="courses" style="display: none;">This field is
        required.</label>
</div>


<div class="mt-3">
    <label>
        Jenis
    </label>
    {!! Form::select('type', $type, null, [
        'class' => 'form-control select2 to-select',
        'id' => 'type',
        'required',
        'placeholder' => 'Pilih Jenis',
    ]) !!}
    <label id="type-error" class="error" for="type" style="display: none;">This field is required.</label>
</div>
<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.rooms') }}" class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
