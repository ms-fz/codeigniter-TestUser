<?php

namespace App\Controllers;

use App\Models\User;

class UserService
{
    public function __construct() {
        $this->userModel = new User();
    }

    public function findAll()
    {
        return $this->userModel->findAll();
    }

    public function create($data)
    {
        return User::insert($data)->getInsertID();
    }

    public function findOne($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }
        
        return $user;
    }

    public function update($id, $data)
    {
        User::update($id, $data);
    }

    public function delete($id = null)
    {
        return User::delete($id);
    }
}
