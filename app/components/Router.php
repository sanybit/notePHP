<?php
class Router
{
	private $routes; //массив маршрутов

	public function __construct() {
		
		// Путь к роутам (ROOT_APP - путь к базовой дериктории,
		$routesPath = ROOT_APP.'config/routes.php'; //путь к файлу с роутами
		
		if(file_exists($routesPath)) { //проверяет существование указанного файла
			// Присваиваем свойству $this->routes массив, из файла routes.php, при помощи - include
			$this->routes = include($routesPath);
		}else {
			$text = 'Маршруты не найдены (routes)';
			include(ROOT."/views/page404.php"); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
	}
	
	// Метод возвращает строку запроса
	private static function getURI() {
		
		if(!empty($_SERVER['REQUEST_URI'])) { //проверяем существование переменной
		
			$scriptURI = str_ireplace('index.php', '', $_SERVER['SCRIPT_NAME']); //путь к файлу скрипта
			if($scriptURI == '/') $scriptURI = '';
			$URI = str_ireplace($scriptURI, '', $_SERVER['REQUEST_URI']); //вычитаем путь из запроса
			$URI = trim($URI, '/'); //удаляет '/' из начала и конца строки
			return $URI; 
		}
	}
	
	// Метод возращает страницу 404
	public static function ErrorPage404($text = 'Страница не найдена'){

		include(ROOT."/views/page404.php");
    }

	// Метод будет принимать управление от фронтконтроллера
	public function run() {

		// Обратимся к методу getURI() (этот метод возвращает строку запроса)
		$uri = $this->getURI();

		// Ищем строку запроса($uri) в наших маршрутах в цикле foreach:
		foreach($this->routes as $uriPattern => $path) {

			// Сравниваем строку запроса ($uri) и данные, которые содержатся в роутах ($uriPattern)
			if(preg_match("~$uriPattern~", $uri)) { //выполняем проверку на соответствие регулярному выражению
				
				// Получаем внутренний путь из внешнего согласно правилу
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri, 1);
				
				// Если есть совпадение, определить какой контроллер и action обрабатывают запрос
				$segments = explode('/', $internalRoute); //разбиваем строку с помощью разделителя

				// Получаем имя контроллера:
				$controllerName = array_shift($segments).'Controller'; //извлекаем первый элемент массива и удаляем его
				$controllerName = ucfirst($controllerName); //делаем первую букву строки заглавной

				// Точно также получаем название экшена:
				$actionName = 'action'.ucfirst(array_shift($segments));

				$controllerObject = new $controllerName; // создаем объект класса контроллера
				
				if(method_exists($controllerObject, $actionName)) { //проверяем существование метода
					call_user_func_array(array($controllerObject, $actionName), $segments); //вызываем метод
				}else {
					$this->ErrorPage404('Нет метода'); //-----------------------DEBUGGING
					die; //прекратить выполнение текущего скрипта
				}
				die; //прекратить выполнение текущего скрипта
			}
		}
	}
}
?>