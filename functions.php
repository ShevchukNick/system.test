<?php
function print_arr($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

// получние названия тестов
function get_tests()
{
    global $pdo;
    $res = $pdo->query("SELECT * FROM test WHERE enable='1'");
    if (!$res) return false;
    $data = [];
    while ($row = $res->fetch()) {
        $data[] = $row;
    }
    return $data;
}

// получние данных теста
function get_test_data($test_id)
{
    if (!$test_id) return;
    global $pdo;
    $query = "SELECT q.question, q.parent_test, a.id, a.answer, a.parent_question
		FROM questions q
		LEFT JOIN answers a
			ON q.id = a.parent_question
		LEFT JOIN test t 
		    ON t.id=q.parent_test
				WHERE q.parent_test = $test_id and t.enable='1'";
    $res = $pdo->query($query);
    $data = null;
    while ($row = $res->fetch()) {
        if (!$row['parent_question']) return false;
        $data[$row['parent_question']][0] = $row['question'];
        $data[$row['parent_question']][$row['id']] = $row['answer'];
    }
    return $data;

}

function pagination($count_questions, $test_data)
{
    $keys = array_keys($test_data);
    $pagination = '<div class="pagination">';
    for ($i = 1; $i <= $count_questions; $i++) {
        $key = array_shift($keys);
        if ($i == 1) {
            $pagination .= '<a class="nav-active" href="#question-' . $key . '">' . $i . '</a>';
        } else {
            $pagination .= '<a  href="#question-' . $key . '">' . $i . '</a>';
        }
    }
    $pagination .= '</div>';
    return $pagination;
}

function get_correct_answers($test)
{
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
    $res = $pdo->query($query);
    $data = null;
    while ($row = $res->fetch()) {
        $data[$row['question_id']] = $row['answer_id'];
    }
    return $data;
}

function get_test_data_result($test_all_data, $result, $post)
{

    foreach ($result as $q => $a) {
        $test_all_data[$q]['correct_answer'] = $a;
        if (!isset($post[$q])) {
            $test_all_data[$q]['incorrect_answer'] = 0;
        }
    }
    foreach ($post as $q => $a) {
        if ($test_all_data[$q]['correct_answer'] != $a) {
            $test_all_data[$q]['incorrect_answer'] = $a;
        }
    }
    return $test_all_data;
}

function print_result($test_all_data_result)
{
    $all_count = count($test_all_data_result);
    $correct_answer_count = 0;
    $incorrect_answer_count = 0;
    $percent = 0;

    foreach ($test_all_data_result as $item) {
        if (isset($item['incorrect_answer'])) $incorrect_answer_count++;
    }
    $correct_answer_count = $all_count - $incorrect_answer_count;
    $percent = ceil($correct_answer_count / $all_count * 100);

    $print_res = '<div class="test-data">';
    $print_res .= '<div class="count-res">';
    $print_res .= "<p>Всего вопросов: {$all_count}</p>";
    $print_res .= "<p>Верно:{$correct_answer_count}</p>";
    $print_res .= "<p>Неверно:{$incorrect_answer_count}</p>";
    $print_res .= "<p>Процент верных ответво:{$percent}</p>";
    $print_res .= '</div>';


    foreach ($test_all_data_result as $id_question => $item) {
        $correct_answer = $item['correct_answer'];
        $incorrect_answer = null;
        if (isset($item['incorrect_answer'])) {
            $incorrect_answer = $item['incorrect_answer'];
            $class = 'question-res error';
        } else {
            $class = 'question-res ok';
        }
        $print_res .= '<div class="$class">';
        foreach ($item as $id_answer => $answer) {
            if ($id_answer === 0) {
                $print_res .= "<p class='q'>$answer</p>";
            } elseif (is_numeric($id_answer)) {
                if ($id_answer == $correct_answer) {
                    $class = 'a ok2';
                } elseif ($id_answer == $incorrect_answer) {
                    $class = 'a error2';
                } else {
                    $class = 'a';
                }
                $print_res .= "<p class='$class'>$answer</p>";
            }
        }
        $print_res .= '</div>';

    }

    $print_res .= '</div>';

    return $print_res;

}