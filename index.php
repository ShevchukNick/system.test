<?php
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// list of tests
$tests = get_tests();
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
        <h1>список тестов</h1>
        <?php foreach ($tests as $test): ?>
            <p><a href="?test=<?=$test['id']?>"><?= $test['test_name'] ?></a></p>
        <?php endforeach; ?>
        <br>
        <hr>
        <br>
    <div class="content">
        вопросы выбранного теста
    </div>
    <?php else: ?>
        <h1>нет тестов</h1>
    <?php endif; ?>

</div>


</body>
</html>
