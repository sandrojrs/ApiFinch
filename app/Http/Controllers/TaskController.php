<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;

class TaskController extends Controller
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
        return Task::paginate();
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

          $diffDate = Helper::DiffDate($request->deadline);
          if (Project::find($request->project_id)->start_date_difference_task($request->deadline) || !$diffDate){             
                return response()->json([
                    'success' => false,
                    'message' => 'the task cannot have a date longer than the project',              
                ]);                     
          }
  
          //Request is valid, create new user
          $user = Task::create([
              'title' => $request->title,
              'description' =>  $request->description,            
              'deadline' => $request->deadline,
              'user_id' => $this->user->id,
              'status' => 'pending',
              'project_id' => $request->project_id,
          ]);
  
          //User created, return success response
          return response()->json([
              'success' => true,
              'message' => 'Task created successfully',
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
        $task = Task::find($id);
    
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task not found.'
            ], 400);
        }    
        return $task;
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
    public function update(Request $request, Task $task)
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

        $diffDate = Helper::DiffDate($request->deadline);
        if (Project::find($request->project_id)->start_date_difference_task($request->deadline) || !$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the task cannot have a date longer than the project',              
              ]);                     
        }
        if($this->user->hasRole(Helper::executorName())){
            $task = $task->update([              
                'status' => $request->status
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Task update successfully'           
            ], Response::HTTP_OK);
        };

        //Request is valid, create new user
        $task = $task->update([
            'title' => $request->title,
            'description' =>  $request->description,
            'deadline' => $request->deadline,
            'user_id' => $this->user->id,
            'project_id' => $request->project_id,
            'status' => $request->status
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Task update successfully'           
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => $th->getMessage()
            ], Response::HTTP_OK);
        } 
    }
}
