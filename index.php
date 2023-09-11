<?php
session_start();

// Включение отображения ошибок на время разработки сайта
ini_set('display_errors', 1); //-----------------------DEBUGGING
error_reporting(E_ALL); //-----------------------------DEBUGGING

date_default_timezone_set('Europe/Samara'); // Устанавливаем часовой пояс

// Создадим константу ROOT
define('ROOT', dirname(__FILE__));
// dirname(__FILE__) - полный путь к файлу на диске
// (функция dirname, псевдоконстанта __FILE__);
// функция dirname — возвращает имя родительского каталога из указанного пути
//define('ROOT_APP', dirname(__FILE__, 2).'/app/'); //создадим константу для папки app
define('ROOT_APP', dirname(__FILE__, 2).'/app/'); //константа для папки app
define('ROOT_HTML', str_ireplace('index.php', '', $_SERVER['SCRIPT_NAME'])); //константа для корня HTML

if(file_exists(ROOT_APP.'config\autorequire.php')) { //проверяет существование указанного файла
		
	require_once(ROOT_APP.'config\autorequire.php'); //подключаем автозагрузчик классов
}else {
	$text = 'AutoRequire не найден';
	include(ROOT."/views/page404.php"); //------------------DEBUGGING
	die; //прекратить выполнение текущего скрипта
}
$router = new Router(); //создаем экземпляр класса Router

$router->run(); //запускаем метод run(), тем самым, передав на него управление
?>