<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedules Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<h2>Schedules Report</h2>

<table>
    <thead>
        <tr>
            <th>Course</th>
            <th>Teacher</th>
            <th>Classroom</th>
            <th>Day</th>
            <th>Start</th>
            <th>End</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $s)
            <tr>
                <td>{{ optional($s->course)->name }}</td>
                <td>{{ optional($s->teacher)->name }}</td>
                <td>{{ optional($s->classroom)->name }}</td>
                <td>{{ $s->day_of_week }}</td>
                <td>{{ $s->start_time }}</td>
                <td>{{ $s->end_time }}</td>
                <td>{{ $s->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
