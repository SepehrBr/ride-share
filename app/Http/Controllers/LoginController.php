<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginWithGhasedakNotification;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        // validate mobile number
        $request->validate([
            'phone' => ['required', 'regex:/^(\+98|0)?9\d{9}$/']
        ]);

        // find or create user
        $user = User::findOrNew([
            'phone' => $request->phone
        ]);

        // error handeling if user couldnt be created or found
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Could not proces a user with given number.'
            ], 401);
        }

        // send code to user
        $user->notify(new LoginWithGhasedakNotification($user->phone));

        // return response as json
        return response()->json([
            'success' => true,
            'message' => 'Verification Code sent.'
        ]);
    }
}
