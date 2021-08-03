<?php

namespace App\Http\Controllers;


use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Services\TaskServices;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;

/**
 * @group Task
 *
 * @authenticated
 */
class TaskController extends Controller
{
    private $taskServices;
    protected $user;

    public function __construct(TaskServices $taskServices)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->taskServices = $taskServices;
    }

    public function index()
    {
        $task = $this->taskServices->All();
        return TaskResource::collection($task);
    }

    public function store(Request $request)
    {
         if(!$this->user->hasRole(Helper::managerName())){           
            return response()->json(['message' => 'User not permissions'], 200);
         };
         //Validate data
          $data = $request->only('title', 'description', 'deadline', 'project_id');
          $validator = Validator::make($data, [
            'title' => 'required',
            'description' =>  'required',
            'deadline' =>  'required',          
            'project_id' =>  'required',
          ]);
  
          //Send failed response if request is not valid
          if ($validator->fails()) {
              return response()->json(['error' => $validator->messages()], 200);
          }

          try {
             DB::beginTransaction();
                $input = $request->all();
                $input['status'] = 'pending';
                $input['user_id'] = $this->user->id;
                //Request is valid, create new user
                $task = $this->taskServices->Create($input);        
                //User created, return success response
             DB::commit();
            //  if (isset($task['success'])){
            //     return new TaskResource($task);
            // }else{
                return $task;
            // }
               
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
        $task = $this->taskServices->findByid($id);
        return new TaskResource($task);
    }
  
    public function update(Request $request, $id)
    {
        //Validate data
        $data = $request->only('title', 'description', 'deadline','project_id','status');
        $validator = Validator::make($data, [
          'title' => 'required',
          'description' =>  'required',
          'deadline' =>  'required',         
          'project_id' =>  'required',
          'status' => ['required', Rule::in(['pending', 'in_progress', 'done'])],
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            DB::beginTransaction();
               $task = $this->taskServices->update($id, $request->all());        
               //User created, return success response
            DB::commit();
            return $task;
              
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
           return $this->taskServices->destroy($id); 
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        } 
    }
}
