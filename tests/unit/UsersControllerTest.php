<?php

namespace tests\unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class UsersControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    // For Migrations
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $refresh = true;

    // For Seeds
    protected $seedOnce = false;
    protected $seed = 'UserSeeder';
    protected $basePath = 'tests/_support/Database/';

    /**
     * Test: GET /users
     */
    public function testFindAll()
    {
        $result = $this->call('get', '/users', [
            'limit' => 3,
        ]);

        $response = json_decode($result->response()->getBody());

        $this->assertTrue($result->isOK());
        $this->assertEquals(5, $response->data->pager->total);
        $this->assertCount(3, $response->data->users);
    }

    /**
     * Test: POST /users
     */
    public function testCreate()
    {
        $result = $this->call('post', '/users', [
            'name' => 'jason',
            'email' => 'jason@gmail.com',
            'phone' => '012-222-9898',
            'age' => 30,
        ]);

        $response = json_decode($result->response()->getBody());

        $this->assertTrue($result->isOK());
        // $this->assertEquals(6, $response->data->userId);
    }

    /**
     * Test: GET /users/:id
     */
    public function testFindOne()
    {
        $id = 1;
        $result = $this->call('get', "/users/$id");

        $response = json_decode($result->response()->getBody());

        $this->assertTrue($result->isOK());
        $this->assertEquals('user1@gmail.com', $response->data->email);
    }

    /**
     * Test: PUT /users/:id
     */
    public function testUpdate()
    {
        $id = 5;
        $body = json_encode([
            'name' => 'jayson',
        ]);

        $result = $this->withBodyFormat('json')->withBody($body)->put("/users/$id");

        $response = json_decode($result->response()->getBody());

        $this->assertTrue($result->isOK());
        $this->assertEquals('jayson', $response->data->name);
    }

    /**
     * Test: DELETE /users/:id
     */
    public function testDelete()
    {
        $id = 1;
        $result = $this->call('delete', "/users/$id");

        $response = json_decode($result->response()->getBody());

        $criteria = [
            'id' => 1,
        ];
        $this->assertTrue($result->isOK());
        $this->dontSeeInDatabase('users', $criteria);

    }
}
