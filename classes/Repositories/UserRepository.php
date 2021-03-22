<?php


namespace Classes\Repositories;


use Classes\Request\RegisterRequest;
use Classes\User;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function createUser(RegisterRequest $request, string $token): bool
    {
        $nick = $this->connection->real_escape_string($request->nickname);
        $pass = md5($request->password);
        $sex = $this->connection->real_escape_string($request->sex);
        $city = $this->connection->real_escape_string($request->city);
        $phone = $this->connection->real_escape_string($request->phone);
        $date = $this->connection->real_escape_string($request->date);

        $result = $this->connection->query("INSERT INTO users (nickname, password, gender, city, phone, date, token)
            VALUES ('{$nick}', '{$pass}', '{$sex}', '{$city}', 
            '{$phone}', '{$date}', '{$token}')");
        return (bool) $result;
    }

    public function getUserByNicknameAndPassword(string $nickname, string $password): ?User
    {
        $nickname = $this->connection->real_escape_string($nickname);
        $password = md5($password);

        $result = $this->connection->query("SELECT * FROM users WHERE nickname = '{$nickname}' AND password = '{$password}'");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return User::createFromArray($resultArray);
    }

    public function getUserByNickname(string $nickname): ?User
    {
        $nickname = $this->connection->real_escape_string($nickname);

        $result = $this->connection->query("SELECT * FROM users WHERE nickname = '{$nickname}' LIMIT 1");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return User::createFromArray($resultArray);
    }

    public function getUserByToken(string $token): ?User
    {
        $token = $this->connection->real_escape_string($token);

        $result = $this->connection->query("SELECT * FROM users WHERE token = '{$token}' LIMIT 1");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return User::createFromArray($resultArray);
    }

    public function getUserById(int $id): ?User
    {
        $result = $this->connection->query("SELECT * FROM users WHERE id = {$id}");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return User::createFromArray($resultArray);
    }
}