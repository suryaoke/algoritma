<table>

    <thead>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th colspan="3" rowspan="4"></th>
        </tr>
        <tr>
            <th colspan="7" style=" text-align: center; font-weight: bold; font-size: 14px;"> JADWAL MATA
                PELAJARAN </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">
                MAN 1 Kota Padang</th>

        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            @php
                $kelasGroups = collect();
            @endphp

            @foreach ($schedules as $key => $schedule)
                @php
                    // Mengelompokkan data berdasarkan kelas
                    $kelas = $schedule->teach->class_room;
                    if (!$kelasGroups->has($kelas)) {
                        $kelasGroups->put($kelas, collect());
                    }
                    $kelasGroups[$kelas]->push($schedule);
                @endphp
            @endforeach
            @foreach ($kelasGroups as $kelas => $schedulesByClass)
                @if ($kelas == '10 TO 1')
                    <th colspan="5"
                        style="border: 2px solid black; text-align: center; font-weight: bold; font-size: 18px;">
                        {{ $kelas }}
                    </th>
                @else
                    <th colspan="3"
                        style="border: 2px solid black; text-align: center; font-weight: bold; font-size: 18px;">
                        {{ $kelas }}
                    </th>
                @endif
            @endforeach

        </tr>
        <tr>
            @foreach ($kelasGroups as $kelas => $schedulesByClass)
                @if ($kelas == '10 TO 1')
                    <th style="border: 2px solid black;">Hari</th>
                    <th style="width:100px  ;border: 2px solid black;">Jam</th>
                @endif
                <th style="width: 80px; border: 2px solid black;">Kode Guru</th>
                <th style="width:150px ;border: 2px solid black;">Mata Pelajaran</th>
                <th style="width:80px ; border: 2px solid black;">Ruangan</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $lastEndTime = null;
            $lastDaysId = null;
            $groupedSchedules = [];
        @endphp

        @foreach ($schedules as $key => $schedule)
            @php
                $originalValue = isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : 0;
                $interval = 40; // Interval waktu dalam menit
                $calculatedValue = $originalValue * $interval;

                $hours = floor($calculatedValue / 60);
                $minutes = $calculatedValue % 60;

                if ($lastEndTime && $schedule->days_id == $lastDaysId) {
                    $startTime = Carbon\Carbon::parse($lastEndTime);
                    $dayName = ''; // Tidak perlu menampilkan nama hari pada baris berikutnya
                } else {
                    if (isset($schedule->day->name_day)) {
                        if ($schedule->day->name_day == 'Senin') {
                            // Adjusting start time for Monday
                            $startTime = Carbon\Carbon::createFromTime(8, 0);

                            // Adding an "Upacara" schedule at the beginning for Monday
                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $schedule->day->name_day,
                                'time' => '07:00 - 08:00',
                                'guru_code' => 'UPACARA',
                                'course_name' => 'UPACARA',
                                'room_name' => 'UPACARA',
                                'type_room' => 'UPACARA',
                            ];

                            $dayName = '';
                        } elseif ($schedule->day->name_day == 'Jumat') {
                            // Adjusting start time for Monday
                            $startTime = Carbon\Carbon::createFromTime(8, 0);

                            // Adding an "Upacara" schedule at the beginning for Monday
                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $schedule->day->name_day,
                                'time' => '07:00 - 08:00',
                                'guru_code' => 'MUHADARAH',
                                'course_name' => 'MUHADARAH',
                                'room_name' => 'MUHADARAH',
                                'type_room' => 'MUHADARAH',
                            ];

                            $dayName = '';
                        } else {
                            $startTime = Carbon\Carbon::createFromTime(7, 0);
                            $dayName = $schedule->day->name_day;
                        }
                    } else {
                        $startTime = Carbon\Carbon::createFromTime(7, 0);
                        $dayName = $schedule->day->name_day;
                    }
                }

                for ($i = 0; $i < $originalValue; $i++) {
                    $endTime = $startTime->copy()->addMinutes($interval);
                    if ($startTime->format('H:i') == '10:00') {
                        $groupedSchedules[$schedule->teach->class_room][] = [
                            'day' => $dayName,
                            'time' => $startTime->format('H:i') . ' - 10:20',
                            'guru_code' => 'ISTIRAHAT',
                            'course_name' => 'ISTIRAHAT',
                            'room_name' => 'ISTIRAHAT',
                            'type_room' => 'ISTIRAHAT',
                        ];
                        $startTime = Carbon\Carbon::parse('10:20'); // Tambahkan waktu istirahat
                    }

                    if ($startTime->format('H:i') < '10:00' && $endTime->format('H:i') > '10:00') {
                        // Hitung waktu selesai untuk interval sebelum istirahat
                        $preBreakEndTime = Carbon\Carbon::createFromTime(10, 0);
                        $groupedSchedules[$schedule->teach->class_room][] = [
                            'day' => $dayName,
                            'time' => $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i'),
                            'guru_code' => $schedule->teach->guru->code_gurus,
                            'course_name' => $schedule->teach->course->name,
                            'room_name' => $schedule->room->name,
                            'type_room' => $schedule->room->type,
                        ];

                        $groupedSchedules[$schedule->teach->class_room][] = [
                            'day' => $dayName,
                            'time' => $preBreakEndTime->format('H:i') . ' - 10:20',
                            'guru_code' => 'ISTIRAHAT',
                            'course_name' => 'ISTIRAHAT',
                            'room_name' => 'ISTIRAHAT',
                            'type_room' => 'ISTIRAHAT',
                        ];
                        $startTime = Carbon\Carbon::parse('10:20'); // Tambahkan waktu istirahat
                    }

                    if ($schedule->day->name_day != 'Jumat') {
                        if ($startTime->format('H:i') == '12:20') {
                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $startTime->format('H:i') . ' - 13:00',
                                'guru_code' => 'ISTIRAHAT',
                                'course_name' => 'ISTIRAHAT',
                                'room_name' => 'ISTIRAHAT',
                                'type_room' => 'ISTIRAHAT',
                            ];
                            $startTime = Carbon\Carbon::parse('13:00'); // Tambahkan waktu istirahat
                        }
                    }

                    if ($schedule->day->name_day != 'Jumat') {
                        // Tambahkan kondisi untuk menangani rentang waktu sebelum jam 09:00
                        if ($startTime->format('H:i') < '12:00' && $endTime->format('H:i') > '12:00') {
                            // Hitung waktu selesai untuk interval sebelum istirahat
                            $preBreakEndTime = Carbon\Carbon::createFromTime(12, 20);

                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i'),
                                'guru_code' => $schedule->teach->guru->code_gurus,
                                'course_name' => $schedule->teach->course->name,
                                'room_name' => $schedule->room->name,
                                'type_room' => $schedule->room->type,
                            ];

                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $preBreakEndTime->format('H:i') . ' - 13:00',
                                'guru_code' => 'ISTIRAHAT',
                                'course_name' => 'ISTIRAHAT',
                                'room_name' => 'ISTIRAHAT',
                                'type_room' => 'ISTIRAHAT',
                            ];

                            // Mulai dari waktu setelah istirahat
                            $startTime = Carbon\Carbon::parse('13:00');
                        }
                    }

                    if ($schedule->day->name_day == 'Jumat') {
                        if ($startTime->format('H:i') == '11:40') {
                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $startTime->format('H:i') . ' - 13:20',
                                'guru_code' => 'ISTIRAHAT',
                                'course_name' => 'ISTIRAHAT',
                                'room_name' => 'ISTIRAHAT',
                                'type_room' => 'ISTIRAHAT',
                            ];

                            $startTime = Carbon\Carbon::parse('13:20'); // Tambahkan waktu istirahat
                        }
                    }

                    if ($schedule->day->name_day == 'Jumat') {
                        if ($startTime->format('H:i') < '11:00' && $endTime->format('H:i') > '10:00') {
                            // Hitung waktu selesai untuk interval sebelum istirahat
                            $preBreakEndTime = Carbon\Carbon::createFromTime(11, 40);

                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i'),
                                'guru_code' => $schedule->teach->guru->code_gurus,
                                'course_name' => $schedule->teach->course->name,
                                'room_name' => $schedule->room->name,
                                'type_room' => $schedule->room->type,
                            ];

                            $groupedSchedules[$schedule->teach->class_room][] = [
                                'day' => $dayName,
                                'time' => $preBreakEndTime->format('H:i') . ' - 13:20',
                                'guru_code' => 'ISTIRAHAT',
                                'course_name' => 'ISTIRAHAT',
                                'room_name' => 'ISTIRAHAT',
                                'type_room' => 'ISTIRAHAT',
                            ];

                            // Mulai dari waktu setelah istirahat
                            $startTime = Carbon\Carbon::parse('13:20');
                        }
                    }

                    $endTime = $startTime->copy()->addMinutes($interval);
                    if ($key === count($schedules) - 1 || $schedule->days_id !== $schedules[$key + 1]->days_id) {
                        if (
                            $i === $originalValue - 1 ||
                            ($key === count($schedules) - 1 && $i === $originalValue - 1)
                        ) {
                            $endTime = Carbon\Carbon::createFromTime(15, 40);
                        }
                    }

                    // Tambahkan waktu istirahat

                    // Menyimpan informasi jadwal ke dalam grup berdasarkan class_room
                    $groupedSchedules[$schedule->teach->class_room][] = [
                        'day' => $dayName,
                        'time' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                        'guru_code' => $schedule->teach->guru->code_gurus,
                        'course_name' => $schedule->teach->course->name,
                        'room_name' => $schedule->room->name,
                        'type_room' => $schedule->room->type,
                    ];

                    $startTime = $endTime;
                    $dayName = ''; // Hanya menampilkan nama hari pada baris pertama
                }

                $lastEndTime = $endTime->format('H:i');
                $lastDaysId = $schedule->days_id;
            @endphp
        @endforeach

        {{-- Menampilkan informasi jadwal berdasarkan class_room --}}
        @foreach ($groupedSchedules as $classRoom => $schedulesByClassRoom)
            @php
                $maxRows = max($maxRows ?? 0, count($schedulesByClassRoom));
            @endphp
        @endforeach


        @for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++)
            <tr style="border: 2px solid black;">
                @foreach ($groupedSchedules as $classRoom => $schedulesByClassRoom)
                    @php
                        $schedule = isset($schedulesByClassRoom[$rowIndex]) ? $schedulesByClassRoom[$rowIndex] : null;
                        $isUpacara =
                            $schedule && $schedule['guru_code'] === 'UPACARA'
                                ? true
                                : $schedule && $schedule['guru_code'] === 'MUHADARAH';

                        $isIstirahat = $schedule && $schedule['guru_code'] === 'ISTIRAHAT';
                        $isIstirahat = $schedule && $schedule['guru_code'] === 'ISTIRAHAT';
                        $room = $schedule && $schedule['type_room'] === 'Praktikum';

                    @endphp

                    @if ($schedule)
                        @if ($classRoom == '10 TO 1')
                            <td style="border: 2px solid black;">
                                {{ $schedule['day'] }}
                            </td>
                            <td style="border: 2px solid black;">
                                {{ $schedule['time'] }}
                            </td>
                        @endif


                        @if ($isUpacara)
                            <td colspan="3"
                                @if ($isUpacara) style="border: 2px solid black; r background-color: gray;" @endif>
                                {{ $schedule['guru_code'] }}</td>
                        @elseif ($isIstirahat)
                            <td colspan="3"
                                @if ($isIstirahat) style="border: 2px solid black;  text-align: center; background-color: yellow;" @endif>
                                {{ $schedule['guru_code'] }}</td>
                        @elseif ($room)
                            <td
                                @if ($room) style=" border: 2px solid black;  background-color: orange;" @endif>
                                {{ $schedule['guru_code'] }}</td>
                        @else
                            <td style="border: 2px solid black;">
                                {{ $schedule['guru_code'] }}</td>
                        @endif

                        @if ($isUpacara || $isIstirahat)
                        @elseif ($room)
                            <td
                                @if ($room) style=" border: 2px solid black;  background-color: orange;" @endif>
                                {{ $schedule['course_name'] }}</td>
                        @else
                            <td style="border: 2px solid black;">
                                {{ $schedule['course_name'] }}</td>
                        @endif

                        @if ($isUpacara || $isIstirahat)
                        @elseif ($room)
                            <td
                                @if ($room) style="border: 2px solid black; text-align: left;  background-color: orange;" @endif>
                                {{ $schedule['room_name'] }}</td>
                        @else
                            <td style=" border: 2px solid black;text-align: left;">
                                {{ $schedule['room_name'] }}</td>
                        @endif
                    @else
                        @if ($classRoom == '10 TO 1')
                            <td></td>
                            <td></td>
                        @endif
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endfor
    </tbody>



</table>
