{!! Form::hidden('idjurusan', isset($jurusans->id) ? $jurusans->id : '', [
    'class' => 'form-control',
    'id' => 'idjurusan',
]) !!}

<div class="mt-3">
    <label for="namejurusans">Nama</label>
    {!! Form::text('name', isset($jurusans->name) ? $jurusans->name : '', [
        'class' => 'form-control',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Nama',
        'id' => 'namejurusans',
    ]) !!}
</div>

<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.jurusans') }}" class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
