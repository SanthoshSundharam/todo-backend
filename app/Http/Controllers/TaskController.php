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
    // public function create(Request $request)
    // {
    //     $request->validate([
    //         "title" => "required|max:255",
    //         "description" => "nullable",
    //     ]);

    //     $task = new Tasks();
    //     $task->title = $request->title;
    //     $task->description = $request->description;
    //     $task->user_id = auth()->id();
    //     $task->save();

    //     return response()->json(['success' => true, 'message' => 'Task created successfully.', 'task' => $task], 201);
    // }

    // public function index()
    // {
    //     // $task->user_id = auth()->id();
    //     return response()->json($this->taskRepo->getAll(Auth::id()));
    // }

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

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'task' => $task,
                'user_id'=>$data['user_id']
            ], 201);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }}


    // public function update(Request $request, $id)
    // {
    //     $task = Tasks::findOrFail($id);
    //     $task->title = $request->input('title');
    //     $task->description = $request->input('description');
    //     $task->user_id = auth()->id();
    //     $task->save();

    //     return response()->json(['success' => true, 'message' => 'Task updated successfully.', 'task' => $task], 201);
    // }


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
        try{
            $this->taskRepo->delete($id, Auth::id());
        return response()->json(['message' => 'Task deleted']);
        }
        catch(\Exception $e){
            return response()->json(['success'=> false,'message'=> ''. $e->getMessage()],);
        }
        
    }
}
