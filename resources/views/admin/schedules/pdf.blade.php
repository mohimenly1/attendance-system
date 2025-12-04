<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Class Schedule</title>
    <style>
        /* ===== Theme (Blue) ===== */
        :root{
            --blue-900:#0b3c64;
            --blue-700:#165a8a;
            --blue-600:#1b6ea7;
            --blue-100:#e9f3fb;
            --border:#bcd3e6;
            --text:#1c2a39;
        }

        @page { margin: 18mm 14mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: var(--text);
            font-size: 12px;
            margin: 0;
        }

        /* ===== Header ===== */
        .header{
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .logo{
            width: 64px; height: 64px; object-fit: contain;
        }
        .uni{
            line-height: 1.25;
        }
        .uni h2{
            margin: 0;
            font-size: 18px;
            color: var(--blue-900);
            letter-spacing: .2px;
        }
        .uni .sub{
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--blue-700);
            font-weight: 600;
        }
        .divider{
            height: 3px;
            background: var(--blue-600);
            margin: 8px 0 18px;
            border-radius: 2px;
        }

        /* ===== Title & meta ===== */
        .title{
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            color: var(--blue-700);
            margin: 0 0 8px;
        }
        .meta{
            text-align: center;
            font-size: 11px;
            color: #57718a;
            margin-bottom: 14px;
        }

        /* ===== Table ===== */
        table{
            width: 100%;
            border-collapse: collapse;
        }
        thead th{
            background: var(--blue-100);
            color: var(--blue-900);
            border: 1px solid var(--border);
            padding: 8px 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        tbody td{
            border: 1px solid var(--border);
            padding: 8px 10px;
            font-size: 12px;
            text-align: center;
        }
        tbody tr:nth-child(odd) td{
            background: #f7fbff;
        }

        /* ===== Footer (optional) ===== */
        .footer{
            margin-top: 14px;
            font-size: 10px;
            color: #7b93a6;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Header with logo -->
    <div class="header">
        <!-- Put your logo file at: public/images/murakib-logo.png -->
        <img class="logo" src="{{ public_path('images/murakib-logo.png') }}" alt="Al-Murakib University Logo">
        <div class="uni">
            <h2>Al-Murakib University</h2>
            <div class="sub">Faculty of Information Technology (IT)</div>
        </div>
    </div>
    <div class="divider"></div>

    <!-- Title -->
    <h1 class="title">Class Schedule</h1>
    <div class="meta">Generated at: {{ now()->format('Y-m-d H:i') }}</div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Course</th>
                <th>Teacher</th>
                <th>Classroom</th>
                <th>From</th>
                <th>To</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->day_of_week }}</td>
                    <td>{{ $schedule->course->name ?? '-' }}</td>
                    <td>{{ $schedule->teacher->name ?? '-' }}</td>
                    <td>{{ $schedule->classroom->name ?? '-' }}</td>
                    <td>{{ substr((string)$schedule->start_time, 0, 5) }}</td>
                    <td>{{ substr((string)$schedule->end_time, 0, 5) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No classes available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">© {{ date('Y') }} Al-Murakib University — Faculty of IT</div>
</body>
</html>
