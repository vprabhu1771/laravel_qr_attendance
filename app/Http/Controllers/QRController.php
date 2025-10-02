<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


use App\Models\Event;

class QRController extends Controller
{
    public function generateQrCode($eventId)
    {
        // Generate a unique identifier (you can customize this based on your needs)
        // $identifier = md5(uniqid());
        // Retrieve the event based on the provided event ID
        $event = Event::find($eventId);

        $qr_info = $event->id . "&" .$event->name . "&" . $event->date . "&" . $event->location;

        // Generate a QR code with the identifier
        $qrCode = QrCode::size(300)->generate($qr_info);

        // Save the identifier to the database or any storage mechanism
        // You can associate this identifier with the current user or a specific event

        // return response()->json(['identifier' => $identifier, 'qrCode' => $qrCode]);
        return view('qr', compact(['event', 'qrCode']));
    }

    
}