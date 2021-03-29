<?php

namespace Classes\Services;


use Classes\Repositories\UserRepositoryInterface;
use Classes\Request\AuthRequest;
use Classes\Request\ProfileRequest;
use Classes\Request\RegisterRequest;
use Classes\User;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function isUniqueNick(string $nickname): bool
    {
        $user = $this->userRepository->getUserByNickname($nickname);
        return $user ? false : true;
    }

    public function addNewUser(RegisterRequest $request): ?string
    {
        $token = md5(microtime(true).time().date('Y-m-d'));
        $result = $this->userRepository->createUser($request, $token);
        return $result ? $token : null;
    }

    public function authAttempt(AuthRequest $request): ?User
    {
        return $this->userRepository->getUserByNicknameAndPassword($request->nickname, $request->password);
    }

    public function getUserByToken(string $token): ?User
    {
        return $this->userRepository->getuserByToken($token);
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function correctPassword(User $user, ProfileRequest $profileRequest): ?bool
    {
        $result = $this->userRepository->getuserByToken($user->token);
        return (md5($profileRequest->password) === $result->password) ? true : false;
    }
}