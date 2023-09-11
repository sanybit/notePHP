<?php
// Метод автоподключения классов (файлов)

spl_autoload_register(function ($class) {
	
	$ok = true;
	$dir_app = scandir(ROOT_APP); //создаём список папок в /app

	foreach($dir_app as $i => $dir) { //проверяем весь список папок

		$path = ROOT_APP.$dir.'/'.$class.'.php'; //получаем путь к файлу из имени класса
		// Если в текущей папке есть такой файл, то выполняем код из него
		if (file_exists($path)) {
			require_once $path;
			$ok = false;
			break;
		}
	}
	if($ok) {
		$text = 'Класс: '.$class.' не найден';
		include(ROOT."/views/page404.php"); //------------------DEBUGGING
		die; //прекратить выполнение текущего скрипта
	}
});
?>