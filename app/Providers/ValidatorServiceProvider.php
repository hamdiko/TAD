<?php

namespace App\Providers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('morph_exists', function ($attribute, $value, $parameters, $validator) {
            if (!$objectType = Arr::get($validator->getData(), $parameters[0], false)) {
                return false;
            }

            if (!$objectType = Relation::getMorphedModel($objectType)) {
                return false;
            }

            if (!class_exists($objectType)) {
                return false;
            }

            return !empty(resolve($objectType)->find($value));
        });

        Validator::extend('has_role', function ($attribute, $value, $parameters, $validator) {
            $role = $parameters[0] ?? false;

            $method = "is" . ucfirst($role);

            $roleExists = \in_array($role, UserRole::all());

            $methodExists = \method_exists(User::class, $method);

            if (!$role || !$roleExists) {
                throw new \Exception("Role {$role} is not supported.");
            }

            if (!$methodExists) {
                throw new \Exception("Method {$method} is not supported.");
            }

            return User::findOrFail($value)->$method();
        });

        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);
        });
    }
}
