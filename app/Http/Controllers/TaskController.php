<?php

namespace App\Http\Controllers;

use App\Events\TaskEvent;
use App\Helpers\ApiResponse;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all(['id', 'name', 'is_complete'])
            ->sortBy('created_at')
            ->groupBy(function ($task) {
                return $task->is_complete ? 'completed' : 'active';
            });

        return ApiResponse::success($tasks->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
        ]);

        $task = new Task([
            'name'          => $request->get('name'),
            'is_complete'   => false,
        ]);
        $task->save();

        event(new TaskEvent(TaskEvent::TYPE_ADD, $task));

        return ApiResponse::success([
            'task' => $task->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
        ]);

        /** @var Task $task */
        $task = Task::findOrFail($id);

        $result = $task->update([
            'name' => $request->get('name'),
        ]);

        if (!$result) {
            return ApiResponse::error();
        }

        event(new TaskEvent(TaskEvent::TYPE_UPDATE, $task));

        return ApiResponse::success();
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'is_complete' => 'required|boolean',
        ]);

        /** @var Task $task */
        $task = Task::findOrFail($id);

        $result = $task->update([
            'is_complete' => boolval($request->get('is_complete')),
        ]);

        if (!$result) {
            return ApiResponse::error();
        }

        event(new TaskEvent(TaskEvent::TYPE_UPDATE, $task));

        return ApiResponse::success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /** @var Task $task */
        $task = Task::findOrFail($id);

        $result = $task->delete();

        if (!$result) {
            return ApiResponse::error();
        }

        event(new TaskEvent(TaskEvent::TYPE_REMOVE, null, intval($id)));

        return ApiResponse::success();
    }
}
