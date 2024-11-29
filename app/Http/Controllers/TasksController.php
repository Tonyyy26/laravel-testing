<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index(TodoList $todoList)
    {
        return TasksResource::collection($todoList->tasks);
    }

    public function store(Request $request, TodoList $todoList)
    {
        $task = $todoList->tasks()->create($request->all());
        return new TasksResource($task);
    }

    public function update(Tasks $task, Request $request)
    {
        $task->update($request->all());
        return new TasksResource($task);
    }

    public function destroy(Tasks $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
