<?php

namespace App\Jobs\User;

use App\Enums\RoleType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Hash;

class AbstractBaseUser
{
    use Dispatchable;

    protected Role $role;

    protected User $user;

    protected function getRole(RoleType $role): static
    {
        $this->role = Role::select(['id', 'slug'])
            ->where('slug', $role->value)
            ->first();

        return $this;
    }

    protected function email(): static
    {
        $this->user->email = $this->email;

        return $this;
    }

    protected function firstName(): static
    {
        $this->user->first_name = $this->firstName;

        return $this;
    }

    protected function lastName(): static
    {
        $this->user->last_name = $this->lastName;

        return $this;
    }

    protected function phone(): static
    {
        $this->user->phone        = isset($this->phone) ? $this->phone : null;

        return $this;
    }

    protected function setCountryCode(): static
    {
        $this->user->country_code = $this->country;

        return $this;
    }

    protected function tempPassword(): static
    {
        $this->user->password = Hash::make('Password@123');

        return $this;
    }

    protected function attachRoles(): static
    {
        $this->user->when($this->role, function () {
            $this->user->roles()->attach($this->role->id);
        });

        return $this;
    }


    protected function createUser(): static
    {

        $this->user->save();

        return $this;
    }

    protected function get(): User
    {
        return $this->user;
    }
}
