<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {    
        $tasks = Task::where('completed', 0)->get();
        return response()->json($tasks);
    }


    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'user' => 'required|email|max:500',
        ]);

        $user = User::where('email', $validated['user'])->first();

        if ($user) {
            $task = new Task([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            $task->user_id = $user->id;

            $task->save();

            return redirect()->back()->with('success', 'Task created successfully.');
        } else {
            return redirect()->back()->withErrors(['user' => 'User not found.']);
        }
    }


    // Actualizar tarea
    public function update(Request $request, $id)
    {
       // Buscar la tarea
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Actualizar el estado 'completed' a 1
        $task->completed = 1;
        $task->save();

        return response()->json(['message' => 'Task completed successfully.', 'task' => $task], 200);
    }
    

    // Eliminar tarea
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
