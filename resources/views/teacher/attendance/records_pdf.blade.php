<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Attendance Record - {{ $course->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            direction: ltr;
            text-align: left;
        }
        h3 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-present {
            color: green;
            font-weight: bold;
        }
        .status-absent {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h3>Attendance Record – {{ $course->name }}</h3>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Status</th>
                <th>Day / Time</th>
            </tr>
        </thead>
        <tbody>
        @foreach($attendanceRecords as $record)
            <tr>
                <td>{{ \Carbon\Carbon::parse($record->attendance_date)->format('Y-m-d') }}</td>

                <td>{{ optional($record->student)->name }}</td>

                <td>
                    <span class="{{ $record->is_present ? 'status-present' : 'status-absent' }}">
                        {{ $record->is_present ? 'Present' : 'Absent' }}
                    </span>
                </td>

                <td>
                    @if($record->schedule)
                        {{ $record->schedule->day_of_week }}
                        ({{ $record->schedule->start_time }} - {{ $record->schedule->end_time }})
                    @else
                        —
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
