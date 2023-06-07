<?php
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (isset($_POST['test'])) {
    $test=(int)$_POST['test'];
    unset($_POST['test']);
    $result = get_correct_answers($test);
    if (!is_array($result)){
        exit('error');
    }
    $test_all_data=get_test_data($test);
    $test_all_data_result=get_test_data_result($test_all_data,$result,$_POST);
//    print_arr($test_all_data);
    echo print_result($test_all_data_result);
    die;
}

// list of tests
$tests = get_tests();


// проверям нажал ли юзер на ссылку с тестом
if (isset($_GET['test'])) {
    $test_id = (int)$_GET['test'];

    $test_data = get_test_data($test_id);
    if (is_array($test_data)) {
        $count_questions = count($test_data);
        $pagination = pagination($count_questions, $test_data);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тестирование</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <?php if ($tests): ?>
        <h1> Доступные ТЕСТЫ</h1>
        <?php foreach ($tests as $test): ?>
            <p><a href="?test=<?= $test['id'] ?>"><?= $test['test_name'] ?></a></p>
        <?php endforeach; ?>
        <br>
        <hr>
        <br>
        <div class="content">

            <?php if (isset($test_data)): ?>

<!--                <h2> Выбранный тест: --><?php //= $test['test_name']?><!--</h2>-->
                <p>всего вопросов: <?= $count_questions ?></p>
                <?= $pagination ?>
                <span class="none" id="test-id"><?= $test_id ?></span>

                <div class="test-data">
                    <?php foreach ($test_data as $id_question => $item): ?>
                        <div class="question" data-id="<?= $id_question ?>" id="question-<?= $id_question ?>">
                            <?php foreach ($item as $id_answer => $answer): ?>
                                <?php if (!$id_answer): ?>
                                    <p class="q"><?= $answer ?></p>
                                <?php else: ?>
                                    <p class="a">
                                        <input type="radio" id="answer-<?= $id_answer ?>"
                                               name="question-<?= $id_question ?>" value="<?= $id_answer ?>">
                                        <label for="answer-<?= $id_answer ?>"><?= $answer ?></label>
                                    </p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="buttons">
                    <button class="center btn" id="btn">Закончить тест</button>
                </div>
            <?php else: ?>
                Выберите тест
            <?php endif; ?>

        </div>
    <?php else: ?>
        <h1>нет тестов</h1>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="scripts.js"></script>

</body>
</html>
