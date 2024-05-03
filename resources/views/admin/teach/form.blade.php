 {!! Form::hidden('idteachs', isset($teachs->id) ? $teachs->id : '', [
     'class' => 'form-control',
     'id' => 'idteachs',
 ]) !!}
 <div class="mt-3">
     <label>
      Guru
     </label>
     {!! Form::select('gurus', $gurus, isset($teachs->gurus_id) ? $teachs->gurus_id : '', [
         'class' => 'form-control select2 to-select',
         'id' => 'gurus',
         'required',
         'placeholder' => 'Pilih Guru',
     ]) !!}
     <label id="gurus-error" class="error" for="gurus" style="display: none;">This field is required.</label>
 </div>
 <div class="mt-3">
     <label>
     Mata Pelajaran
     </label>
     {!! Form::select('courses', $courses, isset($teachs->courses_id) ? $teachs->courses_id : '', [
         'class' => 'form-control select2 to-select',
         'id' => 'courses',
         'required',
         'placeholder' => 'Pilih Mata Pelajaran',
     ]) !!} <label id="courses-error" class="error" for="courses" style="display: none;">This field is
         required.</label>
 </div>
 <div class="mt-3">
     <label>
         Kelas
     </label>
     {!! Form::text('roomclass', isset($teachs->class_room) ? $teachs->class_room : '', [
         'class' => 'form-control',
         'required',
         'maxlength' => '100',
         'placeholder' => 'Masukan Kelas',
         'id' => 'roomclass',
     ]) !!}
 </div>
 <div class="mt-3">
     <label>
         Tahun Akademik
     </label>
     {!! Form::text('year', null, [
         'class' => 'form-control',
         'required',
         'maxlength' => '100',
         'placeholder' => 'Masukan Tahun Akademik',
     ]) !!}
 </div>
 <div class="mt-4">
     <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Save</button>
     <a href="{{ route('admin.teachs') }}"
         class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Cancel</a>
 </div>
