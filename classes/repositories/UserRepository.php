<?php


namespace Classes\Repositories;


class UserRepository extends Repository
{
    public function checkUniqueNick(string $nick){
        $nick = $this->connection->real_escape_string($nick);
        $result = $this->connection->query("SELECT nickname FROM users WHERE nickname = '{$nick}'");
        if ($result->num_rows >= 1) {
            return false;
        }
        return true;
    }
}