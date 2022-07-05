<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Find all users.
     * @param array $queryParams
     * @return array ['users', 'pager']
     */
    public function findAll($queryParams = null)
    {
        $sortBy = 'name';
        $sortOrder = 'ASC';
        $limit = 20;

        $userModelQuery = $this->userModel;

        // Search
        if (isset($queryParams['search'])) {
            $userModelQuery->like('name', $queryParams['search'])
                ->orLike('email', $queryParams['search'])
                ->orLike('phone', $queryParams['search']);
        }

        // Sorting
        if (isset($queryParams['sortBy'])) {
            $sortBy = $queryParams['sortBy'];
        }
        if (isset($queryParams['sortBy']) && isset($queryParams['sortOrder'])) {
            $sortBy = $queryParams['sortBy'];
            $sortOrder = $queryParams['sortOrder'];
        }

        $userModelQuery->orderBy($sortBy, $sortOrder);

        // Limit
        if (isset($queryParams['limit'])) {
            $limit = $queryParams['limit'];
        }

        return [
            'users' => $userModelQuery->paginate($limit),
            'pager' => [
                'total' => $this->userModel->countAll(),
                'currentPage' => $this->userModel->pager->getCurrentPage(),
                'lastPage' => $this->userModel->pager->getPageCount(),
            ],
        ];
    }

    /**
     * Create a new user.
     * @param array $data
     * @return int $newUserId
     */
    public function create($data)
    {
        $this->userModel->insert($data);
        return $this->userModel->getInsertID();
    }

    /**
     * Find one user.
     * @param int $id
     * @return User $user
     * @throws \Exception if user not found
     */
    public function findOne($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        return $user;
    }

    /**
     * Update a user.
     * @param int $id
     * @param array $data
     * @return User $user
     * @throws \Exception Something went wrong
     */
    public function update($id, $data)
    {
        $updated = $this->userModel->update($id, $data);

        if (!$updated) {
            throw new \Exception('Something went wrong', 500);
        }

        return $this->findOne($id);
    }

    /**
     * Delete a user.
     * @param int $id
     * @return bool $deleted
     * @throws \Exception User not found
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        return $this->userModel->delete($id);
    }
}
