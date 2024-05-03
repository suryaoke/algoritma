{!! Form::hidden('idtimenotavailables', isset($timenotavailables->id) ? $timenotavailables->id : '', [
    'class' => 'form-control',
    'id' => 'idtimenotavailables',
]) !!}
<div class="mt-3">
    <label>
        Guru
    </label>
    {!! Form::select(
        'gurus',
        $gurus,
        isset($timenotavailables->gurus_id) ? $timenotavailables->gurus_id : '',
        ['class' => 'form-control select2 to-select', 'id' => 'gurus', 'required', 'placeholder' => 'Pilih Guru'],
    ) !!}
    <label id="gurus-error" class="error" for="gurus" style="display: none;">This field is required.</label>
</div>
<div class="mt-3">
    <label>
        Hari
    </label>
    {!! Form::select('days', $days, isset($timenotavailables->days_id) ? $timenotavailables->days_id : '', [
        'class' => 'form-control select2 to-select',
        'id' => 'days',
        'required',
        'placeholder' => 'Pilih Hari',
    ]) !!}
    <label id="days-error" class="error" for="days" style="display: none;">This field is required.</label>
</div>
<div class="mt-3">
    <label>
        Waktu
    </label>
    {!! Form::select('times', $times, isset($timenotavailables->times_id) ? $timenotavailables->times_id : '', [
        'class' => 'form-control select2 to-select',
        'id' => 'times',
        'required',
        'placeholder' => 'Pilih Waktu',
    ]) !!}
    <label id="times-error" class="error" for="times" style="display: none;">This field is required.</label>
</div>
<div class="mt-4">
    <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
    <a href="{{ route('admin.timenotavailables') }}"
        class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
</div>
