<?php

namespace App\Jobs\User;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUser extends AbstractBaseUser
{
    public function __construct(
        protected readonly string $firstName,
        protected readonly string $lastName,
        protected readonly ?string $phone,
        protected readonly string $country,
        protected User $user,
    ) {
    }

    public static function fromRequest(UpdateUserRequest $updateUserRequest,User $user): static
    {
        return new static(
            $updateUserRequest->getFirstName(),
            $updateUserRequest->getLastName(),
            $updateUserRequest->getPhone(),
            $updateUserRequest->getCountry(),
            $user,
        );
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        return DB::transaction(function () {
            return $this->firstName()
                    ->lastName()
                    ->phone()
                    ->setCountryCode()
                    ->createUser()
                    ->get();
        });
    }
}
