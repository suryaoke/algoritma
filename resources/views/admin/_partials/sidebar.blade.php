  @php

      $id = Request::route('id');

      if ($id !== null) {
          $guruedit = URL::route('admin.guru.edit', ['id' => $id]);
          $mapeledit = URL::route('admin.courses.edit', ['id' => $id]);
          $pengampuedit = URL::route('admin.teach.edit', ['id' => $id]);
          $roomedit = URL::route('admin.room.edit', ['id' => $id]);
          $jurusanedit = URL::route('admin.jurusan.edit', ['id' => $id]);
          $useredit = URL::route('admin.user.edit', ['id' => $id]);
          $timeedit = URL::route('admin.time.edit', ['id' => $id]);
          $dayedit = URL::route('admin.day.edit', ['id' => $id]);
          $notedit = URL::route('admin.timenotavailable.edit', ['id' => $id]);
          $hasilgenerate = URL::route('admin.generates.result', ['id' => $id]);
          $userview = URL::route('user.view', ['id' => $id]);
          $pengajuanedit = URL::route('admin.pengajuantimenotavailable.edit', ['id' => $id]);
      } else {
          $guruedit = 1; // Handle jika parameter id tidak ditemukan dalam URL
          $mapeledit = 1;
          $pengampuedit = 1;
          $roomedit = 1;
          $jurusanedit = 1;
          $useredit = 1;
          $timeedit = 1;
          $dayedit = 1;
          $notedit = 1;
          $hasilgenerate = 1;
          $userview = 1;
          $pengajuanedit = 1;
      }

      $url = url()->current();
      $dashboard = URL::route('admin.dashboard');
      $guru = URL::route('admin.gurus');
      $guruadd = URL::route('admin.guru.create');
      $mapel = URL::route('admin.courses');
      $mapeladd = URL::route('admin.courses.create');
      $pengampu = URL::route('admin.teachs');
      $pengampuadd = URL::route('admin.teach.create');
      $room = URL::route('admin.rooms');
      $roomadd = URL::route('admin.room.create');
      $jurusan = URL::route('admin.jurusans');
      $jurusanadd = URL::route('admin.jurusan.create');
      $user = URL::route('admin.user');
      $useradd = URL::route('admin.user.create');
      $time = URL::route('admin.times');
      $timeadd = URL::route('admin.time.create');
      $day = URL::route('admin.days');
      $dayadd = URL::route('admin.day.create');
      $not = URL::route('admin.timenotavailables');
      $notadd = URL::route('admin.timenotavailable.create');
      $generate = URL::route('admin.generates');
      $adminprofile = URL::route('admin.profile');
      $admineditprofile = URL::route('edit.profile');
      $adminchange = URL::route('change.password');
      $pengajuan = URL::route('admin.pengajuantimenotavailables');
      $gurupengajuan = URL::route('admin.pengajuantimenotavailables');
      $pengajuanadd = URL::route('admin.pengajuantimenotavailable.create');
      $jadwalall = URL::route('jadwal.all');
      $jadwalallkepsek = URL::route('jadwal.all.kepsek');
      $jadwalallguru = URL::route('jadwal.all.guru');
  @endphp

  <nav class="side-nav">
      <a href="{{ route('admin.dashboard') }}" class="intro-x flex items-center pl-5 pt-4">
          <img alt="Midone - HTML Admin Template" class="w-12" src="{{ asset('backend/dist/images/smk1.png') }}">
          <span class="hidden xl:block text-white text-lg ml-3">SMK MUTU Pekanbaru
          </span>
      </a>
      <div class="side-nav__devider my-6"></div>
      <ul>
          <li>
              @if ($url == $dashboard)
                  <a href="{{ route('admin.dashboard') }}" class="side-menu  side-menu--active">
                  @elseif ($url == $adminprofile)
                      <a href="{{ route('admin.dashboard') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $admineditprofile)
                          <a href="{{ route('admin.dashboard') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $adminchange)
                              <a href="{{ route('admin.dashboard') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.dashboard') }}" class="side-menu ">
              @endif
              <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
              <div class="side-menu__title">
                  Dashboard
              </div>
              </a>

          </li>
          {{--  // bagian selain jurusan dan admin --}}

          {{-- @if (Auth::user()->role != '4') 
          <li>
            @if ($url == $room)
                <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                @elseif ($url == $roomadd)
                    <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                    @elseif ($url == $roomedit)
                        <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                        @else
                            <a href="{{ route('admin.rooms') }}" class="side-menu">
            @endif
            <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
            <div class="side-menu__title"> Ruangan</div>
            </a>
        </li>
        @endif --}}
          {{--  // end bagian selain jurusan dan admin --}}

          {{--  // bagian Kepala Sekolah  dan wakil --}}

          @if (Auth::user()->role == '2' || Auth::user()->role == '3')

              <li>
                  @if ($url == $guru)
                      <a href="{{ route('admin.gurus') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $guruadd)
                          <a href="{{ route('admin.gurus') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $guruedit)
                              <a href="{{ route('admin.gurus') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.gurus') }}"class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                  <div class="side-menu__title"> Data Guru</div>
                  </a>
              </li>


              <li>
                  @if ($url == $mapel)
                      <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $mapeladd)
                          <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $mapeledit)
                              <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.courses') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Mata Pelajaran </div>
                  </a>
              </li>
              <li>
                  @if ($url == $room)
                      <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $roomadd)
                          <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $roomedit)
                              <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.rooms') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                  <div class="side-menu__title"> Ruangan</div>
                  </a>
              </li>
          @endif
          {{--  // end bagian Kepala Sekolah  dan wakil --}}


          {{--  // bagian Kepala Sekolah  --}}
          @if (Auth::user()->role == '2')
              <li>
                  @if ($url == $jadwalallkepsek)
                      <a href="{{ route('jadwal.all.kepsek') }}" class="side-menu  side-menu--active">
                      @else
                          <a href="{{ route('jadwal.all.kepsek') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Jadwal Mata Pelajaran </div>
                  </a>
              </li>
          @endif
          {{--  // end bagian Kepala Sekolah  --}}

          {{--  // bagian Jurusan --}}
          @if (Auth::user()->role == '4')
              <li>
                  @if ($url == $time)
                      <a href="javascript:;" class="side-menu  side-menu--active">
                      @elseif ($url == $timeadd)
                          <a href="javascript:;" class="side-menu  side-menu--active">
                          @elseif ($url == $timeedit)
                              <a href="javascript:;" class="side-menu  side-menu--active">
                              @elseif ($url == $day)
                                  <a href="javascript:;" class="side-menu  side-menu--active">
                                  @elseif ($url == $dayedit)
                                      <a href="javascript:;" class="side-menu  side-menu--active">
                                      @elseif ($url == $dayadd)
                                          <a href="javascript:;" class="side-menu  side-menu--active">
                                          @elseif ($url == $notedit)
                                              <a href="javascript:;" class="side-menu  side-menu--active">
                                              @elseif ($url == $notadd)
                                                  <a href="javascript:;" class="side-menu  side-menu--active">
                                                  @elseif ($url == $not)
                                                      <a href="javascript:;" class="side-menu  side-menu--active">
                                                      @elseif ($url == $pengajuan)
                                                          <a href="javascript:;" class="side-menu  side-menu--active">
                                                          @else
                                                              <a href="javascript:;" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                  <div class="side-menu__title">
                      Data Waktu
                      <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                  </div>
                  </a>
                  <ul class="">
                      <li>
                          <a href="{{ route('admin.times') }}" class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                              <div class="side-menu__title"> Jam </div>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.days') }}" class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                              <div class="side-menu__title"> Hari </div>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.timenotavailables') }}" class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                              <div class="side-menu__title"> Waktu Berhalangan</div>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.pengajuantimenotavailables') }}" class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                              <div class="side-menu__title"> Pengajuan Waktu Berhalangan</div>
                          </a>
                      </li>
                  </ul>
              </li>
              <li>
                  @if ($url == $jurusan)
                      <a href="{{ route('admin.jurusans') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $jurusanedit)
                          <a href="{{ route('admin.jurusans') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $jurusanadd)
                              <a href="{{ route('admin.jurusans') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.jurusans') }}" class="side-menu ">
                  @endif

                  <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                  <div class="side-menu__title"> Jurusan </div>
                  </a>
              </li>


              <li>
                  @if ($url == $mapel)
                      <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $mapeladd)
                          <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $mapeledit)
                              <a href="{{ route('admin.courses') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.courses') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Mata Pelajaran </div>
                  </a>
              </li>
              <li>
                  @if ($url == $pengampu)
                      <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $pengampuadd)
                          <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $pengampuedit)
                              <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.teachs') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="landmark"></i> </div>
                  <div class="side-menu__title"> Pengampu </div>
                  </a>
              </li>

              <li>
                  @if ($url == $room)
                      <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $roomadd)
                          <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $roomedit)
                              <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.rooms') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                  <div class="side-menu__title"> Ruangan</div>
                  </a>
              </li>
          @endif
          {{--  //end bagian Jurusan --}}

          {{--  // bagian Guru --}}
          @if (Auth::user()->role == '5')
              <li>
                  @if ($url == $gurupengajuan)
                      <a href="{{ route('admin.pengajuantimenotavailables') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $pengajuanadd)
                          <a href="{{ route('admin.pengajuantimenotavailables') }}"
                              class="side-menu  side-menu--active">
                          @elseif ($url == $pengajuanedit)
                              <a href="{{ route('admin.pengajuantimenotavailables') }}"
                                  class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.pengajuantimenotavailables') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                  <div class="side-menu__title">Pengajuan Waktu Berhalangan </div>
                  </a>
              </li>

              <li>
                  @if ($url == $jadwalallguru)
                      <a href="{{ route('jadwal.all.guru') }}" class="side-menu  side-menu--active">
                      @else
                          <a href="{{ route('jadwal.all.guru') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Jadwal Mata Pelajaran Guru </div>
                  </a>
              </li>
              <li>
                  @if ($url == $room)
                      <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $roomadd)
                          <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $roomedit)
                              <a href="{{ route('admin.rooms') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.rooms') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                  <div class="side-menu__title"> Ruangan</div>
                  </a>
              </li>
              <li>
                  @if ($url == $jadwalallkepsek)
                      <a href="{{ route('jadwal.all.kepsek') }}" class="side-menu  side-menu--active">
                      @else
                          <a href="{{ route('jadwal.all.kepsek') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Jadwal Mata Pelajaran </div>
                  </a>
              </li>
          @endif
          {{--  // End bagian Guru --}}

          {{--  // bagian wakil Kurikulum  --}}
          @if (Auth::user()->role == '3')
              <li>
                  @if ($url == $pengampu)
                      <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                      @elseif ($url == $pengampuadd)
                          <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                          @elseif ($url == $pengampuedit)
                              <a href="{{ route('admin.teachs') }}" class="side-menu  side-menu--active">
                              @else
                                  <a href="{{ route('admin.teachs') }}" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="landmark"></i> </div>
                  <div class="side-menu__title"> Pengampu </div>
                  </a>
              </li>
              <li>
                  @if ($url == $generate)
                      <a href="javascript:;" class="side-menu  side-menu--active">
                      @elseif ($url == $hasilgenerate)
                          <a href="javascript:;" class="side-menu  side-menu--active">
                          @else
                              <a href="javascript:;" class="side-menu">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-plus"></i> </div>
                  <div class="side-menu__title">
                      Generate Jadwal
                      <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                  </div>
                  </a>
                  <ul class="">
                      <li>
                          <a href="{{ route('admin.generates') }}" class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="edit"></i> </div>
                              <div class="side-menu__title"> Input Generate Jadwal</div>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.generates.result', 1) }}"class="side-menu">
                              <div class="side-menu__icon"> <i data-lucide="folder-plus"></i> </div>
                              <div class="side-menu__title"> Hasil Generate Jadwal </div>
                          </a>
                      </li>

                  </ul>
              </li>
          @endif
          {{--  // end bagian wakil Kurikulum  --}}


          {{--  // bagian Admin  --}}
          @if (Auth::user()->role == '1')
              <li>
                  @if ($url == $guru)
                      <a href="javascript:;"class="side-menu  side-menu--active">
                      @elseif ($url == $guruadd)
                          <a href="javascript:;"class="side-menu  side-menu--active">
                          @elseif ($url == $guruedit)
                              <a href="javascript:;" class="side-menu  side-menu--active">
                              @elseif ($url == $user)
                                  <a href="javascript:;" class="side-menu  side-menu--active">
                                  @elseif ($url == $useradd)
                                      <a href="javascript:;" class="side-menu  side-menu--active">
                                      @elseif ($url == $useredit)
                                          <a href="javascript:;" class="side-menu  side-menu--active">
                                          @elseif ($url == $userview)
                                              <a href="javascript:;" class="side-menu  side-menu--active">
                                              @else
                                                  <a href="javascript:;" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                  <div class="side-menu__title">
                      Master
                      <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                  </div>
                  </a>
                  <ul class="">
                      <li>
                          <a href="{{ route('admin.gurus') }}"class="side-menu ">
                              <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                              <div class="side-menu__title"> Data Guru</div>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.user') }}" class="side-menu">

                              <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                              <div class="side-menu__title"> User</div>
                          </a>
                      </li>
                  </ul>
              </li>
          @endif
          {{--  // end bagian Admin  --}}




          {{--  // Bagian Wakil , Admin , Jurusan  --}}
          @if (Auth::user()->role == '3' || Auth::user()->role == '1' || Auth::user()->role == '4')
              <li>
                  @if ($url == $jadwalall)
                      <a href="{{ route('jadwal.all') }}" class="side-menu  side-menu--active">
                      @else
                          <a href="{{ route('jadwal.all') }}" class="side-menu ">
                  @endif
                  <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                  <div class="side-menu__title"> Jadwal Mata Pelajaran </div>
                  </a>
              </li>
          @endif
          {{--  // end Bagian Wakil , Admin , Jurusan  --}}


      </ul>
  </nav>
