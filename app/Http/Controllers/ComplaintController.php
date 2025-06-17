<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('dashboard');
    }

    public function index()
    {
        $complaints = Complaint::with(['student.user', 'responses'])
            ->where('student_id', Auth::user()->student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('complaints/index', [
            'complaints' => $complaints
        ]);
    }

    public function create()
    {
        return Inertia::render('complaints/create');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string',
            'is_anonymous' => 'boolean'
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return back()->withErrors(['error' => 'Student record not found']);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $imagePath = $request->file('image')->storeAs('complaints', $imageName, 'public');

                if (!$imagePath) {
                    throw new \Exception('Failed to store image');
                }
            }

            $complaint = new Complaint();
            $complaint->title = $validated['title'];
            $complaint->type = $validated['type'];
            $complaint->date = $validated['date'];
            $complaint->description = $validated['description'];
            $complaint->is_anonymous = $validated['is_anonymous'] ?? false;
            $complaint->image = $imagePath;
            $complaint->student_id = $student->id;
            $complaint->status = 'pending';

            if (!$complaint->save()) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                throw new \Exception('Failed to save complaint');
            }

            return redirect()->route('complaints.index')
                ->with('success', 'Complaint submitted successfully');

        } catch (\Exception $e) {
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withErrors(['error' => 'Failed to submit complaint: ' . $e->getMessage()]);
        }
    }
}
