{!! Form::hidden('idday', isset($days->id) ? $days->id : '', ['class' => 'form-control', 'id' => 'idday']) !!}
<div class="mt-3">
	<label>
        Kode Hari
    </label>
    {!! Form::text('code_days', null, ['class' => 'form-control', 'required', 'maxlength' => '100', 'placeholder' => 'Masukkan Kode Hari']) !!}
 </div>
<div class="mt-3">
    <label>
        Nama
    </label>
    {!! Form::text('name_day', null, ['class' => 'form-control', 'required', 'maxlength' => '100', 'placeholder' => 'Masukkan Nama Hari']) !!}
</div>
<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.days') }}" class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
