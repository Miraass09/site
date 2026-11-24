<?php
/*
 * =========================================================
 *  Smart College — колледжді басқару жүйесі (1-команда)
 *  Запуск: php -S localhost:8000  ->  http://localhost:8000
 * =========================================================
 */
session_start();

require '3_Аналитик_данных/данные_3D_массив.php';  // данные (3D массив)
require '2_Программист/функции.php';             // функции
require '2_Программист/формы.php';               // обработка форм

$college = normalizeCollege(loadCollege());

$page = $_GET['page'] ?? 'welcome';     // какая страница открыта
$sort = $_GET['sort'] ?? 'name';        // сортировка
$q    = $_GET['q'] ?? '';               // поиск

// Форма отправлена: меняем данные, сохраняем и перезагружаем страницу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $college = handleAction($college, $_POST);
    saveCollege($college);
    header('Location: ' . $_SERVER['REQUEST_URI']); // та же страница, с теми же фильтрами
    exit;
}

$stats    = collegeStats($college);
$messages = $_SESSION['messages'] ?? [];
unset($_SESSION['messages']);

// Приветственная страница — без меню
if ($page === 'welcome') {
    require '1_Дизайнер/приветствие.php';
    exit;
}

// Страница проверки (тестировщик)
if ($page === 'test') {
    require '4_Тестировщик/проверка.php';
    exit;
}

// Страницы дизайнера лежат в папке 1_Дизайнер
$pages = [
    'dashboard' => 'главная',
    'journal'   => 'журнал',
];
if (!isset($pages[$page])) {
    $page = 'dashboard';
}

require '1_Дизайнер/шапка_и_меню.php';
require '1_Дизайнер/' . $pages[$page] . '.php';
require '1_Дизайнер/подвал.php';
