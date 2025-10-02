<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


use App\Models\Event;

use Illuminate\Validation\ValidationException;
use Validator;

class AttendanceController extends Controller
{    

    public function index()
    {
        $attendances = Attendance::with('user', 'event')->get();
            
        return response()->json(['data' => $attendances], 200);
    }

    public function generateQrCode($eventId)
    {
        // Generate a unique identifier (you can customize this based on your needs)
        // $identifier = md5(uniqid());
        // $identifier = "1";
        // Retrieve the event based on the provided event ID
        $event = Event::find($eventId);

        $qr_info = $event->id . "&" .$event->name . "&" . $event->date . "&" . $event->location;

        // Generate a QR code with the identifier
        $qrCode = QrCode::format('png')->size(300)->generate($qr_info);
            
        // dump(base64_encode($qrCode));

        return response()->json(['identifier' => $event, 'qrCode' => base64_encode($qrCode)]);
    }

    public function markAttendance(Request $request, $eventId)
    {
        // 1) Ensure event exists
        $event = Event::find($eventId);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // 2) Validate nested JSON: use dot keys to validate qr_data.*
        $validated = $request->validate([
            'qr_data.event_id'       => 'required|integer',
            'qr_data.event_name'     => 'required|string',
            'qr_data.event_date'     => 'required|date',
            'qr_data.event_location' => 'required|string',
        ]);

        $qrData = $validated['qr_data'];

        // 3) Check QR matches the event
        if ((int)$qrData['event_id'] !== (int)$event->id) {
            return response()->json(['message' => 'Invalid QR code for the event'], 400);
        }

        // 4) Auth check
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // 5) Find attendance for this user+event
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('event_id', $event->id)
                                ->first();

        // 6) If found and already marked -> return
        if ($attendance && $attendance->attendance_time !== null) {
            return response()->json(['message' => 'Attendance already marked'], 200);
        }

        // 7) If found but attendance_time is null -> update it
        if ($attendance) {
            $attendance->attendance_time = now();
            $attendance->save();

            return response()->json([
                'message' => 'Attendance marked successfully',
                'attendance_time' => $attendance->attendance_time
            ], 201);
        }

        // 8) If not found -> create new attendance record and set time
        $new = Attendance::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'attendance_time' => now(),
            // add other fields if required
        ]);

        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance_time' => $new->attendance_time
        ], 201);
    }


    public function getAttendanceHistory(Request $request)
    {
        $user_id = $request->input('id');
        
        // Assuming you want to retrieve the attendance history for the authenticated user
        // $user = Auth::user();

        $attendanceHistory = Attendance::where('user_id', $user_id)->get();

        return response()->json(['attendance_history' => $attendanceHistory], 200);
    }
}