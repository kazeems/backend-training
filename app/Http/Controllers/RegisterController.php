<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
