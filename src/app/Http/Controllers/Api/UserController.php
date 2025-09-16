<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        $user = User::create($request->getUserData());

        return response()->json([
        'message' => 'User created sucessfully',
        'data' => $user,
        'status' => 201
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user,
            'status' => 200
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
    
        $user = User::findOrFail($id);

        $data = $request->validated();
    

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        
        $user->update($data);
    
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);


    }

    public function topDomains()
    {
        $domains = User::pluck('email')
        ->map(function ($email) {
            return explode('@', $email)[1];
        })
        ->countBy()
        ->sortDesc()
        ->take(3);
    
        return response()->json($domains);
    }
}
