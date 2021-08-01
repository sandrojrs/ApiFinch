<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\Project;
use App\Repository\ProjectRepositoryInterface;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function All()
    {
        return $this->projectRepository->All();
    }

    public function FindById(int $id)
    {
        return $this->projectRepository->findById($id);
    }
    public function create(array $data)
    {
        $diffDate = Helper::DiffDate($data['deadline']);
        if (!$diffDate){             
            return response()->json([
                'success' => false,
                'message' => 'the date cannot be less than the current ',              
            ], 400);                     
        }
        return $this->projectRepository->create($data);
    }

    public function update(int $id, array $data)
    {

        $diffDate = Helper::DiffDate($data['deadline']);
        if (!$diffDate){             
              return response()->json([
                  'success' => false,
                  'message' => 'the date cannot be less than the current ',              
              ]);                     
        }
        $projectTasks = Project::with('tasks')->find($id);        
        foreach ($projectTasks->tasks as  $value) {         
            if ( $data['status'] =='done' && $value->status != 'done'){
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry , It is not possible to complete the project as there are still pending tasks',
                ],400);
            }
        }
        $this->projectRepository->update($id, $data);
        return response()->json(['message' => 'User Updated'], 200);
    }

    public function destroy(int $id)
    {
        $this->projectRepository->deleteById($id);
        return response()->json(['message' => 'User Deleted'], 200);
    }
}