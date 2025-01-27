<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param array $data
     * @return User
     */
    public function save(array $data): User
    {
        return $this->userModel->create($data);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return $this->userModel->find($id);
    }
}
