<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;

use Illuminate\Support\Facades\Validator;


class taskController extends Controller
{
    public function index (Request $request) {

        if ($request->has('status')) {
            $status = $request->query('status');

            if (!in_array($status, ['PENDING', 'IN_PROGRESS', 'DONE'])) {
                 $data = [
                    'error' => 'Invalid STATUS value',
                ];
                return response()->json($data, 422);
            }
            $query = Task::query();
            $query->where('status', $status);
            $tasks = $query->get();
        }
        else {
            $tasks = Task::all();
        }

        $data = [
            'tasks' => $tasks,
        ];

        return response()->json($data, 200);
    }

    public function store (Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'nullable',
            'status' => 'required|in:PENDING,IN_PROGRESS,DONE',
        ]);

        if ($validator->fails()) {
            $data = [
                'error' => $validator->errors(),
            ];
            return response()->json($data, 400);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if (!$task) {
            $data = [
                'message' => 'Error on creation',
            ];
            return response()->json($data, 500);
        }

        $data = [
            'task' => $task,
        ];

        return response()->json($data, 201);
    }

    public function show ($id) {

        $task = Task::find($id);

        if (!$task) {
            $data = [
                'message' => 'Not Found',
            ];
            return response()->json($data, 404);
        }

        $data = [
            'task' => $task,
        ];

        return response()->json($data, 200);
    }
 
    public function update (Request $request,$id) {

        $task = Task::find($id);
        
        if (!$task) {
            $data = [
                'message' => 'Not Found',
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'max:100',
            'description' => 'nullable',
            'status' => 'in:PENDING,IN_PROGRESS,DONE',
        ]);

        if ($validator->fails()) {
            $data = [
                'error' => $validator->errors(),
            ];
            return response()->json($data, 400);
        }

        if ($request->has('title')) $task->title = $request->title;
        if ($request->has('description')) $task->description = $request->description;
        if ($request->has('status')){

            //Business Rule: A task cannot be marked as DONE if it's still PENDING
            if ($task->status == 'PENDING' && $request->status == 'DONE') {
                $data = [
                    'message' => 'Not allowed',
                ];
                return response()->json($data, 424);
            }

            $task->status = $request->status;
        }

        $task->save();

        $data = [
            'task' => $task,
        ];

        return response()->json($data, 200);
    }

    public function destroy ($id) {

        $task = Task::find($id);

        if (!$task) {
            $data = [
                'message' => 'Not Found',
            ];
            return response()->json($data, 404);
        }

        $task->delete();

        $data = [
            'message' => 'Task removed',
        ];

        return response()->json($data, 200);
    }
}
