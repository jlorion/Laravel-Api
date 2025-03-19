<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreTodoRequest;
use App\Http\Requests\API\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Get all TODOS
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $todos = Todo::where('user_id', $user->id)->get();
        if ($todos) {
            return response()->json(["status"=>"Success", "todos"=>$todos]);
        }
        return response()->json(["status"=>"Request Error"]);
    }

    /**
     * Create new todo
     */
    public function store(StoreTodoRequest $req): JsonResponse
    {
        $todoObj = new Todo();
        $todo= $req->todo;
        $user = request()->user()->id;
        $todoObj->task = $todo;
        $todoObj->title= $req->title;
        $todoObj->user_id = $user;
        $todoObj->is_done = false;
        $todoObj->save();
        if ($todoObj->save()) {
            return response()->json(["status"=>"task succesfully added"]);
        }
        return response()->json(["status"=>"Error adding task"]);
    }


    /**
     * This endpoint does not exist it is just accounted for by the resource
     */
    public function create()
    {
        return response()->json(["status"=>"This endpoint is not implmeneted!!!"]);
    }
    /**
     * Display specific todo.
     */
    public function show(string $id)
    {
        $user = request()->user()->id;
        $todo = Todo::where("id", $id)->get()->first();
        if(!$todo){
            return  response()->json(["data"=>"this task does not exist"]);
        }
        if ($todo->user_id == $user) {
            return response()->json(["data" => $todo]);
        }
        return response()->json(["Status"=>"Error getting data"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $reqs , string $id)
    {
        $user = request()->user();
        $todo = Todo::where("id", $id)->get()->first();
        $toggle = "argument is_done does nont exist";
        $task = "argument on updating task does not exist";
        if ($user->id == $todo->user_id) {
            # code...
            try {
                if($reqs->exists("is_done")){
                    $todo->is_done = $reqs->is_done;
                    $toggle = "The is_done attribute is updated";
                }
                if ($reqs->exists("todo") || $reqs->exists("title")) {
                    $todo->task = $reqs->todo;
                    $todo->title = $reqs->title;
                    $task = "the tood is updated!!";
                }
                $todo->save();
                return response()->json(["status" => "Successfull", "data" => [$toggle, $task]]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(["status" => "Error updating data"]);
            }
        }
        return response()->json(["Status"=>"Unauthorized"]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = request()->user()->id;
        $todo = Todo::where("id", $id)->get()->first();
        if ($todo->user_id == $user) {
            Todo::destroy($id);
            return response()->json(["status" => "Task Successefully Deleted"]);
        }
        return response()->json(["status" => "Error Deleting Item"]);

    }
}
