<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'    => 'User 1',
                'email'   => 'user1@gmail.com',
                'phone'   => '011-345-6789',
                'age'     => 20,
            ],
            [
                'name'    => 'User 2',
                'email'   => 'user2@gmail.com',
                'phone'   => '012-345-6789',
                'age'     => 20,
            ],
            [
                'name'    => 'User 3',
                'email'   => 'user3@gmail.com',
                'phone'   => '013-345-6789',
                'age'     => 20,
            ],
            [
                'name'    => 'User 4',
                'email'   => 'user4@gmail.com',
                'phone'   => '014-345-6789',
                'age'     => 20,
            ],
            [
                'name'    => 'User 5',
                'email'   => 'user5@gmail.com',
                'phone'   => '015-345-6789',
                'age'     => 20,
            ],
        ];

        $builder = $this->db->table('users');

        foreach ($users as $user) {
            $builder->insert($user);
        }
    }
}
