<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ProjectService;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Validator;


/**
 * @group Project
 *
 * @authenticated
 */

class ProjectController extends Controller
{
    private $projectServices;
    protected $user;

    public function __construct(ProjectService $projectServices)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->projectServices = $projectServices;
    }

    public function index()
    {
        $project = $this->projectServices->All();
        return ProjectResource::collection($project);
    }

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
        try {
            DB::beginTransaction();
                $input = $request->all();                
                $input['user_id'] = $this->user->id;
                $task = $this->projectServices->Create($input);       
             
            DB::commit();
            return  new ProjectResource($task);
                
            } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([                
                'success' => false,
                'message' => $th->getMessage(),               
            ], 400 );
         }        
    }

    public function show($id)
    {
        $project = $this->projectServices->findByid($id);
        return new ProjectResource($project);
    }

    public function update(Request $request, $id)
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
        try {
            DB::beginTransaction();
               $project= $this->projectServices->update($id, $request->all());        
            DB::commit();
            return $project;
              
         } catch (\Throwable $th) {
           DB::rollBack();
           return response()->json([                
               'success' => false,
               'message' => $th->getMessage(),               
           ], 400 );
         }    
    }

    public function destroy($id)
    {
        try {
            return $this->projectServices->destroy($id);
         } catch (\Throwable $th) {
             return response()->json([
                 'success' => true,
                 'message' => $th->getMessage()
             ],400);
         } 
    }
}
