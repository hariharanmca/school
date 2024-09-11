<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Homework;
use App\Models\Mark;
use Carbon\Carbon;

class StudentController extends Controller
{
    
    public function viewHomework()
    {
        try {
            $studentId = auth()->user()->id;
            $homeworks = Homework::where('student_id', $studentId)->get();

            return response()->json($homeworks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch homework'], 500);
        }
    }

    public function updateHomework(Request $request, $homeworkId)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:completed,not_completed',
            'submitted_at' => 'nullable|date',
        ]);

        try {
            $homework = Homework::findOrFail($homeworkId);
            $homework->update($validated);

            return response()->json(['message' => 'Homework updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not update homework'], 500);
        }
    }

    
    public function monitorPerformance()
    {
        try {
            $studentId = auth()->user()->id;
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $marks = Mark::where('student_id', $studentId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $homeworks = Homework::where('student_id', $studentId)
                ->whereBetween('due_date', [$startDate, $endDate])
                ->get();

            $performance = [
                'marks' => $marks,
                'homeworks' => $homeworks,
            ];

            return response()->json($performance);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch performance data'], 500);
        }
    }
}
