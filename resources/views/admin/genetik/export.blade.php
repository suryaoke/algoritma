<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Nama Ruangan</th>
            <th>Kapasitas Ruangan</th>
            <th>Mata Kuliah</th>
            <th>Dosen Pengampu</th>
            <th>Semester</th>
            <th>JP</th>
            <th>Kelas</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $key => $schedule)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ isset($schedule[1]) ? $schedule[1] : '' }}</td>
                <td>{{ isset($schedule[2]) ? $schedule[2] : '' }}</td>
                <td>{{ isset($schedule[3]) ? $schedule[3] : '' }}</td>
                <td>{{ isset($schedule[4]) ? $schedule[4] : '' }}</td>
                <td>{{ isset($schedule[5]) ? $schedule[5] : '' }}</td>
                <td>{{ isset($schedule[6]) ? $schedule[6] : '' }}</td>
                <td>{{ isset($schedule[7]) ? $schedule[7] : '' }}</td>
                <td>{{ isset($schedule[8]) ? $schedule[8] : '' }}</td>
                <td>{{ isset($schedule[9]) ? $schedule[9] : '' }}</td>
                <td>{{ isset($schedule[10]) ? $schedule[10] : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
