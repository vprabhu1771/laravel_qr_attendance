<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function isMobileNoRegistered(Request $request)
    {
        $mobile_no = $request->input('mobile_no');

        // Validate the input if needed

        $user = new User();

        if ($user->isMobileNoRegistered($mobile_no)) {
            return response()->json(['status' => 'success', 'message' => 'Mobile No is registered']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Mobile No is not registered']);
        }
    }
}