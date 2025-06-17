<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentRegistrationController extends Controller
{
    public function create()
    {
        return inertia('Students/Register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'student_number' => 'required|string|unique:students',
            'phone' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student'
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_number' => $request->student_number,
                'phone' => $request->phone
            ]);
        });

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
