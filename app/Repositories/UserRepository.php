<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsersCount(){
        return User::count();
    }

    public function getAllUsers(){
        return User::orderBy('id','desc')->get();
    }

    public function getUserData($search){
        $user =  User::when(isset($search), function($query) use ($search){
            $query->where('name','like','%' . $search . '%');
        })->orderBy('id','desc')->get();

        return $user;
    }
}
