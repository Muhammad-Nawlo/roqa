<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'          => $request->all()['name'],
            'email'         => $request->all()['email'],
            'password'      => Hash::make($request->all()['password']),
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Successful created user.',
            'user' => $user,
        ], 201);
    }

    public function getUsers()
    {
        $users = User::where('isAdmin', '=', false)->get();
        return response()->json([
            'success' => true,
            'users' => $users,
        ], 200);
    }

    public function getAdmins()
    {
        $admins = User::where('isAdmin', '=', true)->get();
        return response()->json([
            'success' => true,
            'admins' => $admins,
        ], 200);
    }
    public function makeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer',],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        $id = $request->all()['id'];
        $user = User::find($id);

        $user->isAdmin = true;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Successful converted user to admin',
        ], 200);
    }
    public function deleteAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer',],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        $id = $request->all()['id'];
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successful deleted admin',
        ], 200);
    }
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        // Check email
        $user = User::where('email', $request->all()['email'])->first();
        if ($user['isAdmin'] == false) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'you are not admin'
                ],
                422
            );
        }


        // Check password
        if (!$user || !Hash::check($request->all()['password'], $user->password)) {
            return response([
                'message' => 'The password is incorrect'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
    
        $user = $request->user();
        $user->tokens()->delete();

        return response([
            'success' => true,
            'message' => 'Logged out'
        ], 200);
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'oldPassword' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $user = $request->user();

        // Check password
        if (!$user || !Hash::check($request->all()['oldPassword'], $user->password)) {
            return response([
                'message' => 'The previous password is incorrect'
            ], 401);
        }


        $user['password'] = Hash::make($request->all()['password']);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Successful changed password',
        ], 200);
    }
}
