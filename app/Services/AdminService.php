<?php

namespace App\Services;

use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminService
{

    public function deleteAdmin($id){
        AdminUser::where('id',$id)->delete();
    }

    public function saveAdminData($data){
        return AdminUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function updateAdminData($data,$id){
        if($data['password'] == null){
            $password = AdminUser::where('id',$id)->select('password')->get();
            return AdminUser::where('id',$id)->update(['name' => $data['name'],'email' => $data['email'],'passowrd' => Hash::make($password)]);
        }else{
            return AdminUser::where('id',$id)->update(['name' => $data['name'],'email' => $data['email'],'password' => Hash::make($data['password'])]);
        }
    }
}
