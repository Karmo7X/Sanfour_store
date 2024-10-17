<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        if(!$users){
            return response()->json([
                'status'=>false,
                'message' => 'No Found Users',
            ],404);
        }else{
            return response()->json([
                'status'=>true,
                'message' => 'Users List',
                'users' => $users->items(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255|string',
        'email' => 'required|unique:users|max:255|string',
        'phone' => 'required|unique:users|max:255|string',
        ]);
        try {
            $user = new User();
           $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->save();
            return response()->json([
                'status'=>true,
                'message' => 'data added successfully',
                'data' => $user
            ],200);

        }catch (ValidationException $e){
             return response()->json([
                 'status'=>false,
                 'errors' => $e->errors()
             ],422);
        }catch (\Exception $exception){
            return response()->json([
                'status'=>false,
                'message' => $exception->getMessage()
            ],500);
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::find($id);
        if(!$user){
            return response()->json([
                'status'=>false,
                'message' => 'user not found',
            ],404);
        }else{
            return response()->json([
                'status'=>true,
                'user' => $user
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::where('id',$id)->get();
        if(!$user){
            return response()->json([
                'status'=>false,
                'message' => 'user not found',
            ]);
        }else{
            return response()->json([
                'status'=>true,
                'user' => $user
            ]);
        }
    }
}
