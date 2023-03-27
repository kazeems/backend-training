<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request) {
        // if (empty($request->firstname)) {
        //     return redirect()->back()->with('error', "name cannot be empty");
        // }
        $request->validate([
            'first_name' => ['required', 'alpha', 'min:3', 'max:10'],
            'email' => ['required', 'string', 'min:3']
        ]);
    }

    public function registerApi(Request $request) {
        $params = $request->validate([
            'name' => ['string', 'required', 'min:3'],
            'email' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:5'],
        ]);

        $name = $params['name'];
        $email = $request->email;
        $password = Hash::make($request->password);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        return response()->json([
            'success' => true,
            'message' => "Registration was successful and data stored!",
            'data' => [
                'submittedName' => $name,
                'submittedEmail' => $email
            ]
        ]);
    }
}
