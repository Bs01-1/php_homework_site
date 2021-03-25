<?php


namespace Classes\Services;


use Classes\Repositories\UserRepositoryInterface;
use Classes\Request\ProfileRequest;

class ProfileService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function UpdateProfileData(ProfileRequest $profileRequest): bool
    {
        return ($this->userRepository->updateCity($profileRequest) && $this->userRepository->updatePhone($profileRequest))
            ? true : false;
    }

    public function UpdatePassword(ProfileRequest $profileRequest)
    {
        return $this->userRepository->updatePassword($profileRequest);
    }
}