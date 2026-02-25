<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'full_name', 'password_hash', 'role', 'is_active'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'full_name' => 'required|max_length[255]',
        'password_hash' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,staff]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters long',
            'is_unique' => 'Username already exists',
        ],
        'full_name' => [
            'required' => 'Full name is required',
        ],
        'password_hash' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long',
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Role must be either admin or staff',
        ],
    ];

    /**
     * Get user by username for authentication
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Get all active users
     */
    public function getActiveUsers()
    {
        return $this->where('is_active', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get all users (active and inactive)
     */
    public function getAllUsers()
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Soft delete user (deactivate)
     */
    public function deactivateUser($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }

    /**
     * Activate user
     */
    public function activateUser($id)
    {
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Create new user with password hashing
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        return $this->insert($data);
    }

    /**
     * Update user with optional password change
     */
    public function updateUser($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        } elseif (isset($data['password'])) {
            unset($data['password']);
        }
        
        return $this->update($id, $data);
    }
}
