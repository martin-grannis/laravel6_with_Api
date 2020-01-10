<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    public function index()
    {
        return Todo::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'completed' => 'required|boolean',
        ]);

        $todo = Todo::create($data);

        return response($todo, 201);
    }

    public function update(Request $request, Todo $todo)
    {
    
        $data = $request->validate([
            'title' => 'required|string',
            'completed' => 'required|boolean',
        ]);

        $todo->update($data);

        return response($todo, 200);
    }
    
    public function destroy(Request $request, Todo $todo)
    {
    
        $todo->delete();

        return response($todo, 200);
    }

}
