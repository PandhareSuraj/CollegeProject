<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Illuminate\Hashing\HashManager;

class MultiModelUserProvider implements UserProviderContract
{
    /** @var \Illuminate\Hashing\Hasher */
    protected $hasher;

    /** @var array<string> */
    protected $models = [
        \App\Models\Administrator::class,
        \App\Models\Provider::class,
        \App\Models\TrustHead::class,
        \App\Models\Principal::class,
        \App\Models\Hod::class,
        \App\Models\Teacher::class,
    ];

    public function __construct()
    {
        $this->hasher = app(HashManager::class)->driver();
    }

    public function retrieveById($identifier)
    {
        $modelType = session('user_model_type');

        if ($modelType && class_exists($modelType)) {
            return $modelType::find($identifier);
        }

        // Fallback: try to find across known models
        foreach ($this->models as $m) {
            $user = $m::find($identifier);
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        $user = $this->retrieveById($identifier);

        if ($user && $user->getRememberToken() && hash_equals($user->getRememberToken(), $token)) {
            return $user;
        }

        return null;
    }

    public function updateRememberToken(AuthenticatableContract $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'] ?? null;
        if (!$email) {
            return null;
        }

        foreach ($this->models as $m) {
            $user = $m::where('email', $email)->first();
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    public function validateCredentials(AuthenticatableContract $user, array $credentials)
    {
        $plain = $credentials['password'] ?? null;
        if ($plain === null) {
            return false;
        }

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Rehash the user's password if required.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @param  bool  $force
     * @return void
     */
    public function rehashPasswordIfRequired(AuthenticatableContract $user, array $credentials, bool $force = false)
    {
        $plain = $credentials['password'] ?? null;

        if ($plain === null) {
            return;
        }

        $hasher = app(\Illuminate\Contracts\Hashing\Hasher::class);

        if (! $hasher->needsRehash($user->getAuthPassword()) && ! $force) {
            return;
        }

        if (method_exists($user, 'forceFill')) {
            $user->forceFill([
                $user->getAuthPasswordName() => $hasher->make($plain),
            ])->save();
        } else {
            $user->{$user->getAuthPasswordName()} = $hasher->make($plain);
            $user->save();
        }
    }
}
