<?php

namespace App;
require_once __DIR__ . '/../config.php';

use PHPUnit\Framework\TestCase;
use App\UserManager;
use App\Database;
use App\User;

class UserManagerTest extends TestCase
{
    private $userManager;
    private $database;
    private $user;

    public function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->user = $this->createMock(User::class);
        $this->userManager = new UserManager($this->database);
        // Start a transaction
        $this->database->beginTransaction();
    }

    public function tearDown(): void
    {
        // Roll back the transaction
        $this->database->rollBack();
    }

    public function testCreateUser()
    {
        $this->user->method('getRole')->willReturn('admin');
        $this->database->method('query')->willReturn(true);

        $result = $this->userManager->createUser('username11', 'email11@example.com', 'newPassword_01010', 'role', $this->user);

        $this->assertTrue($result);
    }

    public function testUpdateUser()
    {
        $this->database->method('query')->willReturn(true);

        $result = $this->userManager->updateUser(1, 'newusername12', 'newemail121@example.com', 'newPassword_00101', 'user');

        $this->assertTrue($result);
    }

    public function testDeleteUser()
    {
        $this->user->method('getRole')->willReturn('admin');
        $this->database->method('query')->willReturn(true);

        $result = $this->userManager->deleteUser(1, $this->user);

        $this->assertTrue($result);
    }

    public function testGetAllUsers()
    {
        $this->database->method('query')->willReturn([
            ['id' => 1, 'username' => 'user1', 'email' => 'user1@example.com', 'role' => 'user'],
            ['id' => 2, 'username' => 'user2', 'email' => 'user2@example.com', 'role' => 'admin'],
        ]);

        $result = $this->userManager->getAllUsers();

        $this->assertCount(2, $result);
        $this->assertEquals('user1', $result[0]['username']);
        $this->assertEquals('user2', $result[1]['username']);
    }

    public function testSearchUsers()
    {
        $this->database->method('query')->willReturn([
            ['id' => 1, 'username' => 'user1', 'email' => 'user1@example.com', 'role' => 'user'],
        ]);

        $result = $this->userManager->searchUsers('user1');

        $this->assertCount(1, $result);
        $this->assertEquals('user1', $result[0]['username']);
    }

    public function testGetUserById()
    {
        $this->database->method('query')->willReturn([
            'id' => 1, 'username' => 'user1', 'email' => 'user1@example.com', 'role' => 'user'
        ]);

        $result = $this->userManager->getUserById(1);

        $this->assertEquals('user1', $result['username']);
    }

    public function testUpdateProfile()
    {
        $this->database->method('query')->willReturn(true);

        $result = $this->userManager->updateProfile(1, 'newusername1211', 'newemail321@example.com', 'newPassword_0011');

        $this->assertTrue($result);
    }

}