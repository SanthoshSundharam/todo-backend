<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "description" => "nullable",
        ]);

        $task = new Tasks();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->user_id = auth()->id();
        $task->save();

        return response()->json(['success' => true, 'message' => 'Task created successfully.', 'task' => $task], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = auth()->id();
        $task->save();

        return response()->json(['success' => true, 'message' => 'Task updated successfully.', 'task' => $task], 201);
    }

    public function destroy($id){
        $task = Tasks::findOrFail($id);
        $task->user_id = auth()->id();
        $task->delete();

        return response()->json(['success' => true, 'message' => 'Task deleted successfully.', 'task' => $task], 200);
    }
}
