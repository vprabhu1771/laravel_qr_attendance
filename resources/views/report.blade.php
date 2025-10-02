<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>

    <style>
        table, th, td {
            width: 100%;
            border-collapse: collapse;

        }

        th, td {
            padding: 10px;
        }

        .absent {
            
        }
    </style>
</head>

<body>
    <h1>Attendance Report</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Event</th>
                <th>Attendance Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presentAttendees as $attendee)
            <tr>
                <td>{{ $attendee->user->name }}</td>
                <td>{{ $attendee->event->name }}</td>
                <td>{{ $attendee->attendance_time ?? 'Absent' }}</td>
            </tr>
            @endforeach

            @foreach($absentAttendees as $attendee)
            <tr>
                <td>{{ $attendee->user->name }}</td>
                <td>{{ $attendee->event->name }}</td>
                <td>Absent</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>