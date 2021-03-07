<?php


namespace Classes\Repositories;


use Classes\Request\RegisterRequest;
use Classes\User;

interface UserRepositoryInterface
{
    public function getUserByNicknameAndPassword(string $nickname, string $password): ?User;

    public function createUser(RegisterRequest $request, string $token): bool;

    public function getUserByNickname(string $nickname): ?User;

    public function getuserByToken(string $token): ?User;
}