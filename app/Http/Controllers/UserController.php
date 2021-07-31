<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $user;

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
        // $user = $this->user->paginate('10');
        return User::with('roles')->paginate();
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
            //Request is valid, create new user
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            //User created, return success response
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),               
            ]);
        }

      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product not found.'
            ], 400);
        }
    
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $data = [$user, $roles, $userRole];
        return response()->json([
            'success' => true,            
            'data' => $data,
        ], 400);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
         //Request is valid, updated user
         $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'cpf' => 'required|cpf',
            'password' => 'required|string|min:6|max:50',
            'roles' =>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
       
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = bcrypt($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
    
        $user->assignRole($request->input('roles'));

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
         $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
}
