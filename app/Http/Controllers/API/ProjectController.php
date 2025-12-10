<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; 

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $projects = Project::where('user_id', Auth::id())->get();
        //return response()->json(Project::all(),200);
        return response()->json($projects,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]); 

        if ($validator->fails()) {
            return response()->json(
                ['message' => 'Validation error',
                 'errors' => $validator->errors()], 400);
        }   

        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['due_date'] = $request->due_date;
        $data['user_id'] = Auth::id();  // assign the authenticated user's id
                                        // kan ook: auth()->id Auth::id()

        $project = Project::create($data);

        return response()->json(
            ['message' => 'Project created', 
             'project' => $project]
            ,200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find project with its tasks belonging to the authenticated user
        $project = Project::with('tasks')->where('user_id', Auth::id())->find($id);

        if(!$project){
            return response()->json(
                ['message' => 'Project not found',
                 'errors' => ['id' => ['No project found with the given id']]
                 ],404);
        }
        
        return response()->json($project,200);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $project = Project::where('user_id', Auth::id())->find($id);

        if(!$project){
            return response()->json(
                ['message' => 'Project not found',
                 'errors' => ['id' => ['No project found with the given id']]
                 ],404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]); 
        
        if ($validator->fails()) {
            return response()->json(
                ['message' => 'Validation error',
                 'errors' => $validator->errors()], 400);
        }   

        $project->name = $request->name;
        $project->description = $request->description;
        $project->due_date = $request->due_date;
        $project->user_id = Auth::id(); 
        $project->save();

        //return response()->json($project,201);

        return response()->json(
            ['message' => 'Project updated', 
             'project' => $project]
            ,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $project = Project::where('user_id', Auth::id())->find($id);

        if(!$project){
            return response()->json(
                ['message' => 'Project not found',
                 'errors' => ['id' => ['No project found with the given id']]
                 ],404);
        }
        $project->delete();

        return response()->json(
            ['message' => 'Project deleted successfully']
            ,204); 
    }
    
}
