<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userRepository;
    protected $userService;

    public function __construct(UserRepository $userRepository,UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vue-pages.user.browse');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }

    //get user table data
    public function getAllUsers(){
        $users = $this->userRepository->getAllUsers();

        return response()->json([
            'data' => $users,
            'status' => 'success',
            'message' => 'Successfully get users'
        ]);
    }

    public function getUserData(Request $request){
        $search = $request->search;
        $user = $this->userRepository->getUserData($search);
        return response()->json([
            'data' => $user,
            'status' => 'success',
            'message' => 'Successfully get search users'
        ]);
    }
}
