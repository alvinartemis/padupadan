<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Stylist; // Pastikan model Stylist Anda ada di sini

class StylistUserProvider implements UserProvider
{
    protected $model;

    public function __construct($hasher, $model)
    {
        $this->hasher = $hasher;
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return Stylist::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getKeyName(), $identifier)
            ->where('remember_token', $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             array_key_exists('password', $credentials))) {
            return null;
        }

        $user = $this->createModel();

        return $user->newQuery()
            ->where('username', $credentials['username'])
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return trim($credentials['password']) === trim($user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        return false; // Tidak ada rehashing karena password tidak di-hash
    }

    protected function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');
        return new $class;
    }
}
