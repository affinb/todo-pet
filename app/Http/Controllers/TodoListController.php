<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;

class TodoListController extends Controller
{
    public function index(Request $request)
    {
        $todo_lists = TodoList::all();
        
        return view('todo_lists.index', compact('todo_lists'));
    }
}
