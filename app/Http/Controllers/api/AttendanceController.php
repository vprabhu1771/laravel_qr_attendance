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
        // Retrieve the event based on the provided event ID
        $event = Event::find($eventId);

        // Extract information from the JSON payload
        $qrData = $request->json('qr_data'); // Assuming 'qr_data' is the key in the JSON payload

        // Validate the structure of the QR code data
        $validator = Validator::make($qrData, [
            'event_id' => 'required|integer',
            'event_name' => 'required|string',
            'event_date' => 'required|date',
            'event_location' => 'required|string',
        ]);

        // If validation fails, throw a ValidationException
        if ($validator->fails()) {
            throw ValidationException::withMessages(['qr_data' => 'Invalid QR code data structure']);
        }

        // Check if the QR code matches the event
        if ($qrData['event_id'] == $event->id) {
            // Check if the user has already marked attendance for this event
            $userId = auth()->user()->id; // Assuming you have user authentication

            // Find the attendance record for the user
            $attendance = Attendance::where('user_id', $userId)->first();

            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('event_id', $event->id)
                // Add this line to check 'attendance_time' is not null
                ->whereNotNull('attendance_time') 
                ->first();

            // Check if attendance record is found and 'attendance_time' is not null
            if ($attendance && $attendance->attendance_time == null) {
                // Update the 'attendance_time' field with the current timestamp
                $result = $attendance->update(['attendance_time' => now()]);                

                return response()->json(['message' => $result . 'Attendance marked successfully'], 201);
            }            
            else {
                print($qrData['event_id'] == $event->id);
                return response()->json(['message' => 'Attendance already marked'], 200);
            }            
        } else {
            return response()->json(['message' => 'Invalid QR code for the event']);
        }
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