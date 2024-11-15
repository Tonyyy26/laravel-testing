<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Tasks;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index(TodoList $todoList)
    {
        return response(Tasks::where(['todo_list_id' => $todoList->id])
            ->get()
        );
    }

    public function store(Request $request, TodoList $todoList)
    {
        $request['todo_list_id'] = $todoList->id;
        return Tasks::create($request->all());
    }

    public function update(Tasks $task, Request $request)
    {
        $task->update($request->all());
        return response($task);
    }

    public function destroy(Tasks $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
