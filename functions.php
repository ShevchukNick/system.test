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

// получние данных теста
function get_test_data($test_id) {
    if (!$test_id) return false;
    global $pdo;
    $query = "SELECT q.question,q.parent_test,a.id,a.answer,a.parent_question
        FROM questions q 
        LEFT JOIN answers a ON q.id=a.parent_question 
        WHERE q.parent_test  = $test_id";
    $res=$pdo->query($query);
    $data=null;
    while ($row=$res->fetch(PDO::FETCH_ASSOC)) {
        if (!$row['parent_question']) return false;
        $data[$row['parent_question']][0]=$row['question'];
        $data[$row['parent_question']][$row['id']]= $row['answer'];
    }
    return $data;

}

function pagination($count_questions,$test_data) {
    $keys=array_keys($test_data);
    $pagination='<div class="pagination">';
    for ($i=1;$i<=$count_questions;$i++) {
        $key=array_shift($keys);
        if ($i==1) {
            $pagination .= '<a class="nav-active" href="#question-' . $key . '">' . $i . '</a>';
        } else {
            $pagination .= '<a  href="#question-' . $key . '">' . $i . '</a>';
        }
    }
    $pagination .='</div>';
    return $pagination;
}