<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
         $tasks = Task::orderBy('created_at', 'desc')->get();
          return view('tasks.index', compact('tasks')); // Perintah untuk menampilkan form HTML
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('tasks.create'); // Perintah untuk menampilkan form HTML
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate(['title' => 'required|max:255']);

        Task::create(['title' => $request->input('title')]);
        
        // Redirect berhasil
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
