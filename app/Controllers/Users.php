<?php

namespace App\Controllers;

use App\Services\UserService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Get user listing
     * @return json
     */
    public function index()
    {
        $queryParams = $this->request->getGet();

        $data = $this->userService->findAll($queryParams);

        return $this->respond(array(
            'code' => 200,
            'status' => 'success',
            'data' => $data,
        ));
    }

    /**
     * Create a user
     * @return json
     */
    public function create()
    {
        $validationRules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'permit_empty|min_length[10]',
            'age' => 'permit_empty|numeric',
        ];

        if (!$this->validate($validationRules)) {
            return $this->fail($this->validator->getErrors());
        }

        $newUserId = $this->userService->create($this->request->getPost());

        return $this->respondCreated(array(
            'code' => 201,
            'status' => 'success',
            'data' => [
                'userId' => $newUserId,
            ],
        ));
    }

    /**
     * Get user by id
     * @param int $id
     * @return json
     */
    public function show($id = null)
    {
        try {
            $user = $this->userService->findOne($id);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e->getCode());
        }

        return $this->respond(array(
            'code' => 200,
            'status' => 'success',
            'data' => $user,
        ));
    }

    /**
     * Update user by id
     * @param int $id
     * @return json
     */
    public function update($id = null)
    {
        $validationRules = [
            'name' => 'permit_empty|string',
            'email' => "permit_empty|valid_email|is_unique[users.email,id,$id]",
            'phone' => 'permit_empty|string|min_length[10]',
            'age' => 'permit_empty|numeric',
        ];

        if (!$this->validate($validationRules)) {
            return $this->fail($this->validator->getErrors());
        }

        $bodyData = $this->request->getJson();

        try {
            $user = $this->userService->update($id, $bodyData);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e->getCode());
        }

        return $this->respond(array(
            'code' => 200,
            'status' => 'success',
            'data' => $user,
        ));
    }

    /**
     * Delete user by id
     * @param int $id
     * @return json
     */
    public function delete($id = null)
    {
        try {
            $this->userService->delete($id);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e->getCode());
        }

        return $this->respond(array(
            'code' => 200,
            'status' => 'success',
        ));
    }
}
