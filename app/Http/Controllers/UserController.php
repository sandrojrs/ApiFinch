<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

/**
 * @group User
 *
 * @authenticated
 * @apiResourceCollection  Mpociot\ApiDoc\Tests\Fixtures\UserResource
 * @apiResourceModel  Mpociot\ApiDoc\Tests\Fixtures\User
 */

class UserController extends Controller
{
    protected $user;
    private $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->userServices = $userServices;
    }

    public function index()
    {
        $user = $this->userServices->All();
        return UserResource::collection($user);
    }
 
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name','cpf','email', 'password', 'roles');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|cpf',
            'password' => 'required|string|min:6|max:50',
            'roles' =>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            DB::beginTransaction();
            //Request is valid, create new user
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = $this->userServices->Create($input);
            $user->assignRole($request->input('roles'));
            DB::commit();
            return new UserResource($user);
           
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
        $user = $this->userServices->findByid($id);
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        //Request is valid, updated user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'cpf' => 'required|cpf',
            'password' => 'required|string|min:6|max:50',
            'roles' =>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            DB::beginTransaction();

            $input = $request->all();
            if(!empty($input['password'])){ 
                $input['password'] = bcrypt($input['password']);
            }else{
                $input = Arr::except($input,array('password'));    
            }           
            $user = $this->userServices->update($id, $input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $users =User::find($id);
            $users->assignRole($request->input('roles'));

            DB::commit();    

            return $user;                   

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
            DB::beginTransaction();
                $user = $this->userServices->destroy($id);
                DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([                
                'success' => false,
                'message' => $th->getMessage(),               
            ], 400 ); 
        }               
    }
}
