<?php


class Auth
{
    public $nick;
    public $paswword;
    public $city;
    public $phone;
    public $date;
    public $sex;

    public function __construct($nick, $paswword, $city, $phone, $date, $sex)
    {
        $this->nick = htmlspecialchars($nick);
        $this->paswword = htmlspecialchars($paswword);
        $this->city = htmlspecialchars($city);
        $this->phone = htmlspecialchars($phone);
        $this->date = htmlspecialchars($date);
        $this->sex = htmlspecialchars($sex);
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

        if (mb_strlen($this->nick) < 4){
            return 'Ваш никнейм слишком короткий! Измените ник и попробуйте снова.';
        }
        if (!$this->checkUniqueNick()){
            return 'Такой ник уже есть в нашей базе! Введите другой ник.';
        }
        if (mb_strlen($this->paswword) < 9){
            return 'Ага! Пытаетесь обмануть js? F5 и вводи пароль заново. Хотя тут только проверка на длину, 
                остальные проверки мне тут лень прописывать.';
        }
        if ($this->city != 'Екатеринбург'){
            return 'Этот сайт функционирует только в Екб, так что пиши в поле города "Екатеринбург".';
        }

        $pos = mb_stripos($this->date, '-');
        $date = mb_substr($this->date, 0, $pos);
        $_SESSION['test'] = $date;
        if ($date > 2002 || $date < 1910){
            return 'Ты слишком молод или стар для этого сайта!';
        }

        $this->addNewUser();
//        header("Location: ../index.php");
    }

    private function setRegisterFormSession (){
        $_SESSION['nick'] = $this->nick;
        $_SESSION['password'] = $this->paswword;
        $_SESSION['city'] = $this->city;
        $_SESSION['phone'] = $this->phone;
        $_SESSION['date'] = $this->date;
        $_SESSION['sex'] = $this->sex;
    }

    private function checkUniqueNick(){
        $mysqli = new mysqli('localhost', 'root', 'root', 'realty');
        $mysqli->set_charset("utf8");

        $result = $mysqli -> query("SELECT nickname FROM users WHERE nickname = '{$this->nick}'");
        if($result->num_rows >= 1){
            return false;
        } else return true;
    }

    private function addNewUser () {
        $pass = (md5($this->paswword));
        $date = date('Y-m-d');
        $_SESSION['test'] = $date;

        $mysqli = new mysqli('localhost', 'root', 'root', 'realty');
        $mysqli->set_charset("utf8");
        $mysqli->query("INSERT INTO users (nickname, password, gender, city, phone, date, createdAt)
            VALUES ('{$this->nick}', '{$pass}', '{$this->sex}', '{$this->city}', '{$this->phone}', '{$this->date}', '{$date}')");
    }
}