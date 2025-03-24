<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
// use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    protected $taskRepo;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }
    public function getUserTasks(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $tasks = $this->taskRepo->getAllTasks($user->id);

        return response()->json(['tasks' => $tasks], 200);
    }

    public function create(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $data['user_id'] = Auth::id();

            $task = $this->taskRepo->create($data);

            return response()->json(['success' => true,'message' => 'Task created successfully.',], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => 'Error: ' . $e->getMessage(),], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'string',
            'description' => 'string|nullable',
            'completed' => 'boolean'
        ]);

        return response()->json($this->taskRepo->update($id, $data, Auth::id()));
    }

    public function destroy($id)
    {
        try {
            $this->taskRepo->destroy($id, Auth::id());
            return response()->json(['message' => 'Task deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '' . $e->getMessage()], );
        }

    }
}
