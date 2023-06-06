<?php
function print_arr($arr) {
    echo '<pre>' .  print_r($arr,true) .'</pre>';
}

// получние названия тестов
function get_tests() {
    global $pdo;
    $res = $pdo->query("SELECT * FROM test WHERE enable='1'");
    if(!$res) return false;
    $data=[];
    while ($row=$res->fetch()) {
        $data[]=$row;
    }
    return $data;
}

// получние данных теста
function get_test_data($test_id) {
    if (!$test_id) return;
    global $pdo;
    $query = "SELECT q.question, q.parent_test, a.id, a.answer, a.parent_question
		FROM questions q
		LEFT JOIN answers a
			ON q.id = a.parent_question
		LEFT JOIN test t 
		    ON t.id=q.parent_test
				WHERE q.parent_test = $test_id and t.enable='1'";
    $res=$pdo->query($query);
    $data=null;
    while ($row=$res->fetch()) {
        if (!$row['parent_question']) return false;
        $data[$row['parent_question']][0] = $row['question'];
        $data[$row['parent_question']][$row['id']] = $row['answer'];
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

function get_correct_answers($test){
    if (!$test) {
        return false;
    }
    global $pdo;
    $query = "SELECT q.id as question_id,a.id as answer_id
                FROM questions q 
                LEFT JOIN answers a
                    ON q.id=a.parent_question
                LEFT JOIN test
                    ON test.id=q.parent_test
                WHERE q.parent_test=$test AND a.correct_answer='1' AND test.enable='1'";
    $res=$pdo->query($query);
    $data=null;
    while ($row=$res->fetch()) {
        $data[$row['question_id']]=$row['answer_id'];
    }
    return $data;
}