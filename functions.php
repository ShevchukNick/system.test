<?php
function print_arr($arr) {
    echo '<pre>' .  print_r($arr,true) .'</pre>';
}

// получние названия тестов
function get_tests() {
    global $pdo;
    $res = $pdo->query("SELECT * FROM test");
    $data=[];
    while ($row=$res->fetch(PDO::FETCH_ASSOC)) {
        $data[]=$row;
    }
    return $data;

}