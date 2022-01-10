<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use Illuminate\Support\Facades\Auth;

class UsersACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return Auth::user()->getRoleNames()->first();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        if (Auth::user()->getRoleNames()->first() === 'Admin') {
            return [
                ['disk' => 'public', 'path' => '*', 'access' => 2],
                ['disk' => 'base', 'path' => '*', 'access' => 2],
            ];
        }

        return [
            ['disk' => 'public', 'path' => '*', 'access' => 1],                                  // main folder - read
            ['disk' => 'base', 'path' => '*', 'access' => 0],                                  // main folder - read
        ];
    }
}
