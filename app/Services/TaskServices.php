<?php

namespace App\Services;


use App\Helper\Helper;
use App\Models\Project;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repository\TaskRepositoryInterface;

class TaskServices
{
    protected $taskRepository;
    protected $user;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function All()
    {
        return $this->taskRepository->All();
    }

    public function FindById(int $id)
    {
        return $this->taskRepository->findById($id);
    }
    public function create(array $data)
    {
        $diffDate = Helper::DiffDate($data['deadline']);
        if (Project::find($data['project_id'])->start_date_difference_task($data['deadline']) || !$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the task cannot have a date longer than the project',              
              ]);                     
        }
        $task = $this->taskRepository->create($data);
        return response()->json([
            'success' => true,
            'message' => 'Task create success',              
        ]);  
    }

    public function update(int $id, array $data)
    {
        $diffDate = Helper::DiffDate($data['deadline']);
        if (Project::find($data['project_id'])->start_date_difference_task($data['deadline']) || !$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the task cannot have a date longer than the project',              
              ]);                     
        }

        if($this->user->hasRole(Helper::executorName())){
            $this->taskRepository->update($id, $data['status']);
            return response()->json([
                'success' => true,
                'message' => 'Task update successfully'           
            ], Response::HTTP_OK);
        }else{
           $this->taskRepository->update($id, $data);
            return response()->json([
                'success' => true,
                'message' => 'Task update successfully'           
            ], Response::HTTP_OK);
        }
        $this->taskRepository->update($id, $data);
        return response()->json(['message' => 'Task Updated'], 200);
    }

    public function destroy(int $id)
    {
        if(!$this->user->hasRole(Helper::managerName())){           
            return response()->json(['message' => 'User not permissions'], 200);
         };
        $this->taskRepository->deleteById($id);
        return response()->json(['message' => 'Task Deleted'], 200);
    }
}