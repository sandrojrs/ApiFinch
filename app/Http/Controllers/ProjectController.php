<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user->project()->with('tasks')->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //Validate data
          $data = $request->only('name','deadline');
          $validator = Validator::make($data, [
              'name' => 'required|string',
              'deadline' => 'required|date',           
              'status' => 'pending',
          ]);
  
          //Send failed response if request is not valid
          if ($validator->fails()) {
              return response()->json(['error' => $validator->messages()], 200);
          }

          $diffDate = Helper::DiffDate($request->deadline);
        if (!$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the date cannot be less than the current ',              
              ]);                     
        }
  
          //Request is valid, create new user
          $user = Project::create([
              'name' => $request->name,
              'deadline' => $request->deadline,
              'user_id' => $this->user->id
          ]);
  
          //User created, return success response
          return response()->json([
              'success' => true,
              'message' => 'User created successfully',
              'data' => $user
          ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = project::with('tasks')->find($id);
    
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, project not found.'
            ], 400);
        }
    
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->only('name','deadline','status');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'deadline' => 'required|date',        
            'status' => ['required', Rule::in(['pending', 'in_progress', 'done'])],   
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $diffDate = Helper::DiffDate($request->deadline);
        if (!$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the date cannot be less than the current ',              
              ]);                     
        }

        $projectTasks = Project::with('tasks')->find($project->id);        
        foreach ($projectTasks->tasks as  $value) {         
            if ( $request->status =='done' && $value->status != 'done'){
                return response()->json([
                    'success' => true,
                    'message' => 'Sorry , It is not possible to complete the project as there are still pending tasks',
                    'data' => $project
                ]);
            }
        }
        try {
            $project = $project->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',               
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()               
            ], Response::HTTP_OK);
        }       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
       $project->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ], Response::HTTP_OK);
    }
}
