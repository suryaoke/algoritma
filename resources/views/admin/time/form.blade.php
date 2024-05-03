
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> <!-- Include jQuery Validation plugin -->

{!! Form::hidden('idtimes', isset($times->id) ? $times->id : '', ['class' => 'form-control', 'id' => 'idday']) !!}

<div class="mt-3">
    <label>
        Kode Waktu
    </label>
    {!! Form::text('code_times', null, [
        'class' => 'form-control',
        'required',
        'maxlength' => '100',
        'placeholder' => 'Masukkan Kode Waktu',
    ]) !!}
</div>


<div class="mt-3">
    <label for="time_begin">
        Waktu Mulai
    </label>
    <div class="">
        <input type="time" id="time_begin" name="time_begin" class="form-control" required maxlength="100"
            placeholder="Masukkan Waktu Mulai">
    </div>
</div>


<div class="mt-3">
    <label for="time_begin">
        Waktu Akhir
    </label>
    <div class="">
        <input type="time" id="time_finish" name="time_finish" class="form-control" required maxlength="100"
            placeholder="Masukkan Waktu Mulai">
    </div>
</div>


<div class="mt-3">
    <label>
        Jp
    </label>
    <input type="text" id="jp" name="jp" class="form-control" required maxlength="100"
    placeholder="Jumlah JP">
       <label id="jp-error" class="error" for="jp" style="display: none;">This field is required.</label>
</div>

<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.times') }}" class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
