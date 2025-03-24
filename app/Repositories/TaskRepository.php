<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{

    public function getAllTasks($userId)
    {
        return Tasks::where('user_id', $userId)->get();
    }

    public function getById($id, $userId)
    {
        return Tasks::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data)
    {

        return Tasks::create($data);
    }

    public function update($id, array $data)
    {
        $task = Tasks::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function destroy($id)
    {
        $task = Tasks::findOrFail($id);
        return $task->delete();
    }
}
