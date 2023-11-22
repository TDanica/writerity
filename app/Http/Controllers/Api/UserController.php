<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(CreateUserRequest $request)
    {
        $userData = $request->validated();

        DB::beginTransaction();
        try {

            $user = new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = $userData['password'];
            $user->save();

            foreach ($userData['skills'] as $skill) {
                $user->skills()->attach($skill);
            }

            DB::commit();

            return response()->json(['message' => "User successfully created"], 200);
        } catch (\Exception $ex) {

            DB::rollBack();
            return response()->json(['message' => "An error has occured while creating a user"], 500);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $userData = $request->validated();

        try {
            $user = User::findOrFail($id);
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = $userData['password'];
            $user->save();


            $user->skills()->sync($userData['skills']);


            return response()->json(['message' => "User successfully updated"], 200);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => "User not found"], 404);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => "An error has occured while updating user"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->skills()->detach();
            
            $user->delete();

            return response()->json(['message' => "User successfully deleted"], 200);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => "User not found"], 404);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => "An error has occured while deleting user"], 500);
        }
    }
}
