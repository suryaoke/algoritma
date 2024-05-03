<!DOCTYPE html>
<html>

<head>
    <title>Jadwal Mata Pelajaran Semester Ganjil</title>
    <style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center; margin-bottom: 11px; font-family: Calibri, sans-serif;">JADWAL MATA PELAJARAN</h2>
    <h3 style="text-align: center; margin-bottom: 11px; font-family: Calibri, sans-serif;">SMK MUHAMMADIYAH 1 PEKANBARU
    </h3>
    <h4 style="text-align: center; margin-bottom: 8px; font-family: Calibri, sans-serif;">Kelas: {{ $kelas }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Kode Guru</th>
                <th>Mata Pelajaran</th>
                <th>Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $lastEndTime = null; // Inisialisasi waktu terakhir
                $lastDaysId = null; // Inisialisasi ID hari terakhir
            @endphp
            @foreach ($schedules as $key => $schedule)
                @php
                    $originalValue = isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : 0;
                    $interval = 40; // Interval waktu dalam menit
                    $calculatedValue = $originalValue * $interval;

                    // Hitung jam dan menit berdasarkan nilai yang dihitung
                    $hours = floor($calculatedValue / 60);
                    $minutes = $calculatedValue % 60;

                    // Format rentang waktu
                    if ($lastEndTime && $schedule->days_id == $lastDaysId) {
                        // Gunakan waktu terakhir sebagai waktu mulai
                        $startTime = Carbon\Carbon::parse($lastEndTime);
                        $dayName = ''; // Tidak perlu menampilkan nama hari pada baris berikutnya
                    } else {
                        // Gunakan waktu standar 07:00 sebagai waktu mulai jika days_id berbeda

                        if ($schedule->day->name_day == 'Senin') {
                            $startTime = Carbon\Carbon::createFromTime(7, 0);
                            $endTime = Carbon\Carbon::createFromTime(8, 0);

                            echo '<tr>';
                            echo '<td>' . $schedule->day->name_day . '</td>';
                            echo '<td>' . $startTime->format('H:i') . ' - ' . $endTime->format('H:i') . '</td>';
                            echo '<td>UPACARA</td>';
                            echo '<td>UPACARA</td>'; // Tidak ada guru untuk upacara
                            echo '<td>UPACARA</td>'; // Tidak ada mata pelajaran untuk upacara

                            echo '</tr>';

                            // Set waktu mulai untuk jadwal berikutnya
                            $startTime = $endTime;
                            $dayName = ''; // Hanya menampilkan nama hari pada baris pertama
                        } elseif ($schedule->day->name_day == 'Jumat') {
                            $startTime = Carbon\Carbon::createFromTime(7, 0);
                            $endTime = Carbon\Carbon::createFromTime(8, 0);

                            echo '<tr>';
                            echo '<td>' . $schedule->day->name_day . '</td>';
                            echo '<td>' . $startTime->format('H:i') . ' - ' . $endTime->format('H:i') . '</td>';
                            echo '<td>MUHADARAH</td>';
                            echo '<td>MUHADARAH</td>'; // Tidak ada guru untuk upacara
                            echo '<td>MUHADARAH</td>'; // Tidak ada mata pelajaran untuk upacara

                            echo '</tr>';

                            // Set waktu mulai untuk jadwal berikutnya
                            $startTime = $endTime;
                            $dayName = ''; // Hanya menampilkan nama hari pada baris pertama
                        } else {
                            if (isset($schedule->day->name_day)) {
                                if ($schedule->day->name_day == 'Senin' || $schedule->day->name_day == 'Jumat') {
                                    $startTime = Carbon\Carbon::createFromTime(8, 0);
                                } else {
                                    $startTime = Carbon\Carbon::createFromTime(7, 0);
                                }
                                $dayName = $schedule->day->name_day;
                            } else {
                                $startTime = Carbon\Carbon::createFromTime(7, 0);
                                $dayName = $schedule->day->name_day;
                            }
                            // Menampilkan nama hari pada baris pertama
                        }

                        // Menampilkan nama hari pada baris pertama
                    }
                    for ($i = 0; $i < $originalValue; $i++) {
                        // Hitung waktu selesai untuk setiap interval
                        $endTime = $startTime->copy()->addMinutes($interval);
                        if ($startTime->format('H:i') == '10:00') {
                            echo '<tr>';
                            echo '<td>' . $dayName . '</td>';
                            echo '<td>' . $startTime->format('H:i') . ' - 10:20</td>';
                            echo '<td>ISTIRAHAT</td>';
                            echo '<td>ISTIRAHAT</td>';
                            echo '<td>ISTIRAHAT</td>';

                            echo '</tr>';
                            $startTime = Carbon\Carbon::parse('10:20'); // Tambahkan waktu istirahat
                        }
                        if ($startTime->format('H:i') < '10:00' && $endTime->format('H:i') > '10:00') {
                            // Hitung waktu selesai untuk interval sebelum istirahat
                            $preBreakEndTime = Carbon\Carbon::createFromTime(10, 0);

                            // Cetak baris untuk interval sebelum istirahat
                            echo '<tr>';
                            echo '<td>' . $dayName . '</td>';
                            echo '<td>' . $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i') . '</td>';

                            echo '<td>' . $schedule->teach->guru->code_gurus . '</td>';
                            echo '<td>' . $schedule->teach->course->name . '</td>';
                            echo '<td>' . $schedule->room->name . '</td>';
                            echo '</tr>';

                            // Cetak baris untuk waktu istirahat setelah interval sebelum istirahat
                            echo '<tr>';
                            echo '<td>' . $dayName . '</td>';
                            echo '<td>' . $preBreakEndTime->format('H:i') . ' - 10:20</td>';
                            echo '<td>ISTIRAHAT</td>';
                            echo '<td>ISTIRAHAT</td>';
                            echo '<td>ISTIRAHAT</td>';

                            echo '</tr>';

                            // Mulai dari waktu setelah istirahat
                            $startTime = Carbon\Carbon::parse('10:20');
                        }
                        if ($schedule->day->name_day != 'Jumat') {
                            if ($startTime->format('H:i') == '12:20') {
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $startTime->format('H:i') . ' - 13:00</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';

                                echo '</tr>';
                                $startTime = Carbon\Carbon::parse('13:00'); // Tambahkan waktu istirahat
                            }
                        }
                        if ($schedule->day->name_day != 'Jumat') {
                            // Tambahkan kondisi untuk menangani rentang waktu sebelum jam 09:00
                            if ($startTime->format('H:i') < '12:00' && $endTime->format('H:i') > '12:00') {
                                // Hitung waktu selesai untuk interval sebelum istirahat
                                $preBreakEndTime = Carbon\Carbon::createFromTime(12, 20);

                                // Cetak baris untuk interval sebelum istirahat
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i') . '</td>';

                                echo '<td>' . $schedule->teach->guru->code_gurus . '</td>';
                                echo '<td>' . $schedule->teach->course->name . '</td>';
                                echo '<td>' . $schedule->room->name . '</td>';
                                echo '</tr>';

                                // Cetak baris untuk waktu istirahat setelah interval sebelum istirahat
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $preBreakEndTime->format('H:i') . ' - 13:00</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';

                                echo '</tr>';

                                // Mulai dari waktu setelah istirahat
                                $startTime = Carbon\Carbon::parse('13:00');
                            }
                        }

                        if ($schedule->day->name_day == 'Jumat') {
                            if ($startTime->format('H:i') == '11:40') {
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $startTime->format('H:i') . ' - 13:20</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';

                                echo '</tr>';
                                $startTime = Carbon\Carbon::parse('13:20'); // Tambahkan waktu istirahat
                            }
                        }
                        if ($schedule->day->name_day == 'Jumat') {
                            if ($startTime->format('H:i') < '11:00' && $endTime->format('H:i') > '10:00') {
                                // Hitung waktu selesai untuk interval sebelum istirahat
                                $preBreakEndTime = Carbon\Carbon::createFromTime(11, 40);

                                // Cetak baris untuk interval sebelum istirahat
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $startTime->format('H:i') . ' - ' . $preBreakEndTime->format('H:i') . '</td>';

                                echo '<td>' . $schedule->teach->guru->code_gurus . '</td>';
                                echo '<td>' . $schedule->teach->course->name . '</td>';
                                echo '<td>' . $schedule->room->name . '</td>';
                                echo '</tr>';

                                // Cetak baris untuk waktu istirahat setelah interval sebelum istirahat
                                echo '<tr>';
                                echo '<td>' . $dayName . '</td>';
                                echo '<td>' . $preBreakEndTime->format('H:i') . ' - 13:20</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';
                                echo '<td>ISTIRAHAT</td>';

                                echo '</tr>';

                                // Mulai dari waktu setelah istirahat
                                $startTime = Carbon\Carbon::parse('13:20');
                            }
                        }
                        $endTime = $startTime->copy()->addMinutes($interval);
                        if ($key === count($schedules) - 1 || $schedule->days_id !== $schedules[$key + 1]->days_id) {
                            if ($i === $originalValue - 1 || ($key === count($schedules) - 1 && $i === $originalValue - 1)) {
                                $endTime = Carbon\Carbon::createFromTime(15, 40);
                            }
                        }

                        // Cetak baris untuk setiap interval
                        echo '<tr>';
                        echo '<td>' . $dayName . '</td>';
                        echo '<td>' . $startTime->format('H:i') . ' - ' . $endTime->format('H:i') . '</td>';

                        echo '<td>' . $schedule->teach->guru->code_gurus . '</td>';
                        echo '<td>' . $schedule->teach->course->name . '</td>';
                        echo '<td>' . $schedule->room->name . '</td>';
                        echo '</tr>';

                        // Simpan waktu selesai untuk digunakan pada iterasi selanjutnya
                        $startTime = $endTime;
                        $dayName = ''; // Hanya menampilkan nama hari pada baris pertama
                    }

                    // Simpan waktu selesai terakhir dan ID hari terakhir untuk digunakan pada iterasi selanjutnya
                    $lastEndTime = $endTime->format('H:i');
                    $lastDaysId = $schedule->days_id;
                @endphp
            @endforeach
        </tbody>
    </table>




    @php
        $kepsek = App\Models\user::where('role', '2')->first();
        $wakil = App\Models\user::where('role', '3')->first();
    @endphp

    <div style="text-align: right; margin-top: 50px;">
        <div style="display: inline-block; text-align: left;">
            <p style="text-align: right; margin-top: 50px;">
                Pekanbaru, 2023
            </p>
            <p>Waka Kurikulum</p>
            <br />
            <br />
            <br />
            <br />
            <br />
            <p>Irwandy, S.Pd.</p>
            <p>NBM. 813 208</p>
        </div>
    </div>


</body>

</html>
