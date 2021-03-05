<?php


namespace Classes\Repositories;


use Classes\Request\RegisterRequest;
use Classes\Request\AuthRequest;

class UserRepository extends Repository
{
    public function checkUniqueNick(string $nick): bool
    {
        $nick = $this->connection->real_escape_string($nick);
        $result = $this->connection->query("SELECT nickname FROM users WHERE nickname = '{$nick}'");
        if ($result->num_rows >= 1) {
            return false;
        }
        return true;
    }

    public function addNewUser (RegisterRequest $request): bool
    {
        $nick = $this->connection->real_escape_string($request->nick);
        $pass = md5($request->password);
        $sex = $this->connection->real_escape_string($request->sex);
        $city = $this->connection->real_escape_string($request->city);
        $phone = $this->connection->real_escape_string($request->phone);
        $date = $this->connection->real_escape_string($request->date);
        $nowDate = date('Y-m-d');

        $result = $this->connection->query("INSERT INTO users (nickname, password, gender, city, phone, date, createdAt)
            VALUES ('{$nick}', '{$pass}', '{$sex}', '{$city}', 
            '{$phone}', '{$date}', '{$nowDate}')");

        if ($request) {
            return true;
        }
        return false;
    }

    public function checkCorrectPassword (AuthRequest $request) {
        $nick = $this->connection->real_escape_string($request->nickname);
        $password = md5($request->password);

        $result = $this->connection->query("SELECT nickname FROM users WHERE nickname = '{$nick}' AND password = '{$password}'");
        if (($result ->fetch_assoc())['nickname'] == $nick){
            return true;
        }
        return false;
    }


}