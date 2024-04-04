<?php

namespace App\Repositories;

use App\Models\AdminUser;

class AdminRepository
{
    public function getAllUsersCount(){
        return AdminUser::count();
    }
    
    public function getAllAdminsTableData(){
        return AdminUser::orderBy('id','desc')->get();
    }

    public function getAdminData ($search){
        return AdminUser::when(isset($search),function($query) use ($search){
            $query->where('name','like','%'.$search. '%');
            $query->where('email','like','%'.$search. '%');
        })->orderBy('id','desc')->get();
    }

}