<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return $this->user->project()->paginate();
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
          ]);
  
          //Send failed response if request is not valid
          if ($validator->fails()) {
              return response()->json(['error' => $validator->messages()], 200);
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
        $project = project::find($id);
    
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
        $data = $request->only('name','deadline');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'deadline' => 'required|date',           
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $project = $project->fill($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project
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
