<?php

namespace App\Jobs\User;

use App\Enums\RoleType;
use App\Http\Requests\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUser extends AbstractBaseUser
{
    public function __construct(
        protected readonly string $email,
        protected readonly string $firstName,
        protected readonly string $lastName,
        protected readonly ?string $phone,
        protected readonly string $country,
        protected RoleType $roleType,
    ) {
    }

    public static function fromRequest(CreateUserRequest $createUserRequest, RoleType $roleType): static
    {
        return new static(
            $createUserRequest->getEmail(),
            $createUserRequest->getFirstName(),
            $createUserRequest->getLastName(),
            $createUserRequest->getPhone(),
            $createUserRequest->getCountry(),
            $roleType,
        );
    }


    private function make(): static
    {
        $this->user = new User;

        return $this;
    }

    // private function setPasswordToken(): static
    // {
    //     $createdUser                              = $this->user;
    //     $token                                    = Password::getRepository()->create($createdUser);
    //     $this->user->onboard_password_reset_token = base64_encode($token);
    //     $this->user->update();

    //     return $this;
    // }

    // protected function notify(): static
    // {
    //     $token = $this->user->onboard_password_reset_token;
    //     $this->user->notify(new SetPasswordEmailNotification($token, $this->user));

    //     return $this;
    // }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        return DB::transaction(function () {
            return $this->make()
                   ->getRole($this->roleType)
                    ->email()
                    ->firstName()
                    ->lastName()
                    ->phone()
                    ->setCountryCode()
                    ->tempPassword()
                    ->createUser()
                    ->attachRoles()
                    ->get();
        });
    }
}
