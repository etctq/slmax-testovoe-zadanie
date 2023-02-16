<?php
if (class_exists('task1')) {
    require_once('task1.php');
} else {
    die();
}


class task2
{

    public $user_id;


    public function __construct(array $params)
    {
        $options = [];
        foreach ($params as $key => $param) {
            $options[] = "`{$key}` {$param['operand']} '{$param[$key]}'";
        }
        $sql = "SELECT id FROM Human WHERE {$options[0]}";
        if (($a = count($options)) > 1) {
            for ($i = 1; $i < $a; $i++) {
                $sql .= " and {$options[$i]}";
            }
        }
        $sql .= ';';
        $connect = task1::connect();
        $result = (mysqli_query($connect, $sql));
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $this->user_id[] .= $row['id'];
            }
        }
    }


    public function find_users() : array
    {
        $task_users = [];
        foreach ($this->user_id as $id) {
            $task_users[] = new task1($id);
        }

        return $task_users;
    }


    public function delete_users() : void
    {
        foreach ($this->user_id as $id){
            task1::delete($id);
        }

    }
}