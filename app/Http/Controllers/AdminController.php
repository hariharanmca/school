<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function addTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'specialization' => 'required|string|max:255',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'teacher',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'specialization' => $validated['specialization'],
            ]);

            return response()->json(['message' => 'Teacher added successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not add teacher'], 500);
        }
    }
    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'grade' => 'required|string|max:10',
            'section' => 'required|string|max:10',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'grade' => $validated['grade'],
                'section' => $validated['section'],
            ]);

            return response()->json(['message' => 'Student added successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not add student'], 500);
        }
    }
    
    public function editTeacher(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'specialization' => 'sometimes|string|max:255',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            $teacher = Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $teacher->update([
                    'specialization' => $validated['specialization'] ?? $teacher->specialization,
                ]);
            }

            return response()->json(['message' => 'Teacher updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not update teacher'], 500);
        }
    }

 
     
    public function editStudent(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'grade' => 'sometimes|string|max:10',
            'section' => 'sometimes|string|max:10',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $student->update([
                    'grade' => $validated['grade'] ?? $student->grade,
                    'section' => $validated['section'] ?? $student->section,
                ]);
            }

            return response()->json(['message' => 'Student updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not update student'], 500);
        }
    }

  
    public function deleteTeacher($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
            Teacher::where('user_id', $user->id)->delete();
            $user->delete();

            return response()->json(['message' => 'Teacher deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete teacher'], 500);
        }
    }

   
    public function deleteStudent($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'student')->firstOrFail();
            Student::where('user_id', $user->id)->delete();
            $user->delete();

            return response()->json(['message' => 'Student deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete student'], 500);
        }
    }
}
