<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $userMock;
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the User model
        $this->userMock = Mockery::mock(User::class);

        // Inject the mock into the service
        $this->userService = new UserService($this->userMock);
    }

    public function testSave()
    {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
        ];

        $this->userMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($this->userMock);

        // Act
        $result = $this->userService->save($data);

        // Assert
        $this->assertInstanceOf(User::class, $result);
    }

    public function testGetByIdReturnsUser()
    {
        // Arrange
        $userId = 1;

        $this->userMock
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($this->userMock);

        // Act
        $result = $this->userService->getById($userId);

        // Assert
        $this->assertInstanceOf(User::class, $result);
    }

    public function testGetByIdReturnsNull()
    {
        // Arrange
        $userId = 1;

        $this->userMock
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act
        $result = $this->userService->getById($userId);

        // Assert
        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
