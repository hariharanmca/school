<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Homework;
use App\Models\Mark;

class TeacherController extends Controller
{
   
    public function assignMarks(Request $request, $studentId)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'marks' => 'required|integer',
        ]);

        try {
            $student = Student::where('user_id', $studentId)->firstOrFail();
            Mark::updateOrCreate(
                ['student_id' => $student->id, 'subject' => $validated['subject']],
                ['marks' => $validated['marks']]
            );

            return response()->json(['message' => 'Marks assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not assign marks'], 500);
        }
    }

    
    public function assignHomework(Request $request, $studentId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        try {
            $student = Student::where('user_id', $studentId)->firstOrFail();
            Homework::create([
                'student_id' => $student->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
            ]);

            return response()->json(['message' => 'Homework assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not assign homework'], 500);
        }
    }
}

