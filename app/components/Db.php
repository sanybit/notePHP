<?php
class Db
{
	public static function getConnection() {
	
		$paramsPath = ROOT_APP.'config/db_params.php'; //путь файла db_params.php в папке config
		
		if(file_exists($paramsPath)) { //проверяет существование указанного файла
			$params = include($paramsPath); //получаем параметры соединения
		}else {
			$text = 'Параметры соединения с базой отсутствуют';
			include(ROOT."/views/page404.php"); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}

		// Создаем объект класса PDO
		$dsn = "mysql:host={$params['host']}; dbname={$params['dbname']}";
		try {
			$db = new PDO($dsn , $params['user'], $params['password']);
		}
		catch(Exception $ex) {
			$text = 'Не верные данные соединения с БД';
			include(ROOT."/views/page404.php"); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
		return $db; //возвращаем объект класса PDO $db = Db::getConnection
	}
}
?>