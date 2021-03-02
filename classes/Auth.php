<?php

namespace Classes;

use Classes\Repositories\Repository;
use Classes\Repositories\UserRepository;
use Classes\Request\AuthRequest;

class Auth
{
    protected AuthRequest $request;
    protected UserRepository $repository;

    public function __construct(AuthRequest $request, UserRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public static function sessionCheck ()
    {
        if (isset($_SESSION['auth'])){
            return true;
        } else return false;
    }

    public function registration ()
    {
        $this->setRegisterFormSession();

        if (mb_strlen($this->request->nick) < 4){
            return 'Ваш никнейм слишком короткий! Измените ник и попробуйте снова.';
        }
        if (!$this->repository->checkUniqueNick($this->request->nick)){
            return 'Такой ник уже есть в нашей базе! Введите другой ник.';
        }
        if (mb_strlen($this->request->password) < 9){
            return 'Ага! Пытаетесь обмануть js? F5 и вводи пароль заново. Хотя тут только проверка на длину, 
                остальные проверки мне тут лень прописывать.';
        }
        if ($this->request->city != 'Екатеринбург'){
            return 'Этот сайт функционирует только в Екб, так что пиши в поле города "Екатеринбург".';
        }

        $pos = mb_stripos($this->request->date, '-');
        $date = mb_substr($this->request->date, 0, $pos);
        $_SESSION['test'] = $date;
        if ($date > 2002 || $date < 1910){
            return 'Ты слишком молод или стар для этого сайта!';
        }

        $this->addNewUser();
//        header("Location: ../index.php");
    }

    private function setRegisterFormSession (){
        $_SESSION['nick'] = $this->request->nick;
        $_SESSION['password'] = $this->request->password;
        $_SESSION['city'] = $this->request->city;
        $_SESSION['phone'] = $this->request->phone;
        $_SESSION['date'] = $this->request->date;
        $_SESSION['sex'] = $this->request->sex;
    }



    private function addNewUser () {
        $pass = (md5($this->request->password));
        $date = date('Y-m-d');
        $_SESSION['test'] = $date;

        $mysqli = \models\Connect::getConnect();
        $mysqli->set_charset("utf8");
        $mysqli->query("INSERT INTO users (nickname, password, gender, city, phone, date, createdAt)
            VALUES ('{$this->request->nick}', '{$pass}', '{$this->request->sex}', '{$this->request->city}', 
            '{$this->request->phone}', '{$this->request->date}', '{$date}')");
    }
}