<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Repositories\AdminRepository;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminRepository;
    protected $adminService;

    public function __construct(AdminRepository $adminRepository,AdminService $adminService)
    {
        $this->adminRepository = $adminRepository;
        $this->adminService = $adminService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vue-pages.admin.browse');
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
    public function store(AdminRequest $request)
    {
        $data = $request->all();
        $admin = $this->adminService->saveAdminData($data);

        return response()->json([
            'data' => $admin,
            'message' => 'Successfully create admin.',
            'status' => 'success'
        ]);
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
    public function update(AdminUpdateRequest $request,$id)
    {
        $data = $request->all();
        $admin = $this->adminService->updateAdminData($data,$id);

        return response()->json([
            'data' => $admin,
            'status' => 'success',
            'message' => 'Admin updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $this->adminService->deleteAdmin($id);
        return response()->json([
            'status' => "success",
            'message' => "Successfully delete admin"
        ]);
    }

    public function getAllAdminsTableData(){
        $admins = $this->adminRepository->getAllAdminsTableData();

        return response()->json([
            'status' => 'success',
            'data' => $admins,
            'message' => 'Successfully get admins',
        ]);
    }

    public function getAdminData(Request $request){
        $search = $request->search;
        $admin = $this->adminRepository->getAdminData($search);

        return response()->json([
            'data' => $admin,
            'message' => 'Successfully get admin search data',
            'status' => 'success'
        ]);
    }
}
