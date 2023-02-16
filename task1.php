<?php


class task1
{
    public $id;
    public $name;
    public $surname;
    public $date_birth;
    public $gender;
    public $city_birth;


    function __construct()
    {
        $a = func_get_args();
        if (func_num_args() == 5) {
            self::validate($a[0], $a[1], $a[2], $a[3], $a[4]);
            $this->name = $a[0];
            $this->surname = $a[1];
            $this->date_birth = $a[2];
            $this->gender = $a[3];
            $this->city_birth = $a[4];
        } else {
            $this->find($a[0]);
        }
    }

    public function find($id)
    {
        $conn = $this->connect();
        $sql = "SELECT * FROM Human WHERE id = '$id'";
        if ($human = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
            $this->id = $id;
            $this->name = $human['name'];
            $this->surname = $human['surname'];
            $this->date_birth = $human['date_birth'];
            $this->gender = $human['gender'];
            $this->city_birth = $human['city_birth'];
        } else {
            echo 'Ошибка поиска данных';
        }
    }


    public function save(): bool
    {
        $conn = $this->connect();
        $sql = "INSERT INTO `User`.`Human` (`name`,`surname`,`date_birth`,`gender`,`city_birth`)
                VALUES ('$this->name', '$this->surname', '$this->date_birth', '$this->gender', '$this->city_birth');";
        if (mysqli_query($conn, $sql)) {
            echo 'Успешно';
            return true;
        } else {
            echo 'Ошибка';
            return false;
        }
    }


    static function delete($id): bool
    {
        $conn = self::connect();
        $sql = "DELETE FROM Human WHERE id = $id;";
        if (mysqli_query($conn, $sql)) {
            echo 'Успешно';
            return true;
        } else {
            echo 'Ошибка';
            return false;
        }
    }


    static function calc_age($age) {
        $age_timestamp = strtotime($age);
        $age = date('Y') - date('Y', $age_timestamp);
        if (date('connection', $age_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }


    const MALE = 0;
    const FEMALE = 1;
    public static function fromBinary($gender): string
    {
        switch ($gender) {
            case self::MALE:
                return "Муж";
            case self::FEMALE:
                return "Жен";
            default:
                throw new InvalidArgumentException("Ошибка");
        }
    }

    static function connect(): object
    {
        $connect = mysqli_connect('localhost', 'root', '', 'User');
        if (!$connect) {
            die('Ошибка подключения');
        }
        return $connect;
    }


    public function find_format_human(): object
    {
        $a = new stdClass();
        $a->id = $this->id;
        $a->name = $this->name;
        $a->surname = $this->surname;
        if (func_num_args() == 1) {
            if ($b = strlen(func_get_arg('0')) == 1) {
                $a->gender_string = self::fromBinary($b);
                $a->date_birth = $this->date_birth;
            }
            if ($b = strlen(func_get_arg('0')) > 1) {
                $a->age = self::calc_age($b);
                $a->gender = $this->gender;
            }
        }
        if (func_num_args() == 2) {
            $a->age = self::calc_age(func_get_arg(0));
            $a->gender_string = self::fromBinary(func_get_arg(1));
        }
        $a->city_birth = $this->city_birth;

        return (object)$a;
    }


    static function validate($name, $surname, $date_birth, $gender, $city_birth): void
    {
        if ($name !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $name)) {
                die('Неверное имя');
            }
        }
        if ($surname !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $surname)) {
                die('Неверная фамилия');
            }
        }
        if ($date_birth !== null) {
            if (!preg_match("/^[1-9]+$/i", $date_birth)) {
                die('Неверная дата рождения');
            }
        }
        if ($gender !== null) {
            if (!preg_match("/^[0-1]+$/i", $gender)) {
                die('Неверно указан пол');
            }
        }
        if ($city_birth !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $city_birth)) {
                die('Неверный город');
            }
        }
    }
}