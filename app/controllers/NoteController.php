<?php
class NoteController
{
	// Список задач (main)
	public function actionIndex($page = 1, $param = false) {

		if(!empty($param)) { //проверяем существование переменной
		
			Router::ErrorPage404('Лишний параметр в списке'); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
		
		if($_SESSION['login_note'] && Note::checkingLogin($_SESSION['login_note'], false, true)) {}
		else {
			$_SESSION['login_note'] = false;
			header('Location: /note/login');
		}
		
		$parameters = array();
		$pageTask = 10; // количество заметок на странице

		// Вычисляем количество страниц
		$pagesCount = ceil(Note::getCountList() / $pageTask); //Округляем дробь в большую сторону
		if($pagesCount == 0) $pagesCount = 1;
		if($page > $pagesCount) {
			Router::ErrorPage404('Страница не существует'); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
		$parameters['pagesCount'] = $pagesCount;
		$parameters['page'] = $page;
		
		$page = ($page-1)*$pageTask; //вычисляем сколько записей пропускать
		
		$taskList = array();
		$taskList = Note::getTaskList($pageTask, 'date', $page); //обращение к статическому методу модели
		$parameters['taskList'] = $taskList;

		$templates = array(
			'note',
			'main'
		);
		
		LoadingPages::view($templates, $parameters);
	}

	//Метод входа пользователя (login)
	public function actionLogin() {
		sleep(1);
		if(!empty($_POST['button']) && $_POST['button'] == 'enter') { // Если пришли по нажатию кнопки вход
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['password'])) {
	
				echo Note::enterLogin($_POST['login'], $_POST['password']);
				die; //прекратить выполнение текущего скрипта
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'exit') { // Если пришли по нажатию кнопки выход
			$_SESSION['login_note'] = false;
			echo 'Выход выполнен';
			die; //прекратить выполнение текущего скрипта
		}
		
		$templates = array(
			'note',
			'login'
		);
		$parameters = array();			
		LoadingPages::view($templates, $parameters);
	}
	
	//Метод настроек пользователя (settings)
	public function actionSettings() {
		if($_SESSION['login_note'] && Note::checkingLogin($_SESSION['login_note'], false, true)) {}
		else {
			$_SESSION['login_note'] = false;
			header('Location: /note/login');
		}
		$message = '???';
		
		if(!empty($_POST['button']) && $_POST['button'] == 'del_account') { // если кнопка удалить аккаунт
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['password'])) {
				if(($message = Note::enterLogin($_POST['login'], $_POST['password'])) == 'Вход выполнен') {
					if(($message = Note::deleteUser($_POST['login'])) == 'Аккаунт удалён') {
						$_SESSION['login_note'] = false;
						echo 'Аккаунт удалён';
						die; //прекратить выполнение текущего скрипта
					}
					echo $message;
				}else {
					echo $message;
				}
				die; //прекратить выполнение текущего скрипта
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'save_email') { // если кнопка сменить почту
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['email'])) {
				if($_POST['login'] == $_SESSION['login_note']) {
					echo Note::saveEmail($_POST['email']);
					die; //прекратить выполнение текущего скрипта
				}else {
					echo 'Логин не авторизован';
					die; //прекратить выполнение текущего скрипта
				}
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'save_telegram') { // если кнопка сменить телеграм
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['telegram'])) {
				if($_POST['login'] == $_SESSION['login_note']) {
					echo Note::saveTelegram($_POST['telegram']);
					die; //прекратить выполнение текущего скрипта
				}else {
					echo 'Логин не авторизован';
					die; //прекратить выполнение текущего скрипта
				}
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'save_password') { // если кнопка сменить пароль
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['password_current']) && !empty($_POST['password_new'])) {
				if($_POST['login'] == $_SESSION['login_note']) {
					echo Note::savePassword($_POST['password_current'], $_POST['password_new']);
					die; //прекратить выполнение текущего скрипта
				}else {
					echo 'Логин не авторизован';
					die; //прекратить выполнение текущего скрипта
				}
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		$templates = array(
			'note',
			'settings'
		);
		$parameters = Note::getUserItemByLogin();		
		LoadingPages::view($templates, $parameters);
	}
	
	//Метод регистрации пользователя (registration)
	public function actionRegistration($activation = false, $param = false) {
		sleep(1);
		if($param) { //проверяем существование переменной
			Router::ErrorPage404('Лишний параметр в списке'); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
		
		if($activation) { //проверяем существование кода активации
			
			$message = Note::performActivation($activation);
			
			$templates = array(
				'note',
				'confirmation'
			);
			$parameters = array(
				'message' => $message
			);			
			LoadingPages::view($templates, $parameters);
			die; //прекратить выполнение текущего скрипта
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'register') { // Если пришли по нажатию кнопки зарегестрироваться
			// Если все поля заполнены
			if(!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['email'])) {
				$task_array = array(
						'login' => $_POST['login'],
						'password' => $_POST['password'],
						'email' => $_POST['email'],
						'telegram' => @$_POST['telegram']
					);
				echo Note::addLogin($task_array);
				die; //прекратить выполнение текущего скрипта
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		$templates = array(
			'note',
			'registration'
		);
		$parameters = array();			
		LoadingPages::view($templates, $parameters);
	}
	
	// Метод добавления записи в таблицу (add)
	public function actionAdd($id = 0, $param = false) {
		if(!empty($param)) { //проверяем существование переменной
		
			Router::ErrorPage404('Лишний параметр в списке'); //------------------DEBUGGING
			die; //прекратить выполнение текущего скрипта
		}
		if($_SESSION['login_note'] && Note::checkingLogin($_SESSION['login_note'], false, true)) {}
		else {
			$_SESSION['login_note'] = false;
			header('Location: /note/login');
		}
		
		$header = 'Новая заметка';
		if($id) $header = 'Изменить заметку';
		
		if(!empty($_POST['button']) && $_POST['button'] == 'status') { // Если пришли по выбору статуса
			// Если все поля заполнены
			if(!empty($_POST['id']) && !empty($_POST['status'])) {
				$task_array = array(
						'id' => $_POST['id'],
						'status' => $_POST['status']
					);
				echo Note::editStatus($task_array);
				die; //прекратить выполнение текущего скрипта
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'save') { // Если пришли по нажатию кнопки сохранить
			// Если все поля заполнены
			if(!empty($_POST['font']) && !empty($_POST['color']) && !empty($_POST['task'])) {
				$task_array = array(
						'id' => $_POST['id'],
						'task' => $_POST['task'],
						'color' => $_POST['color'],
						'font' => $_POST['font']
					);
				echo Note::addTask($task_array);
				die; //прекратить выполнение текущего скрипта
			}else {
				echo 'Не все поля заполнены';
				die; //прекратить выполнение текущего скрипта
			}
		}
		
		if(!empty($_POST['button']) && $_POST['button'] == 'del') { // Если пришли по нажатию кнопки удалить
			echo Note::deleteTask($_POST['id']);
			die; //прекратить выполнение текущего скрипта
		}
		
		$taskList = Note::getTaskItemById($id); //запрашиваем данные редактируемой строки
		
		if($id) {
			if(!($_SESSION['login_note'] == $taskList['login'])) { //проверяем доступ пользователя к заметке
				Router::ErrorPage404('Доступ запрещён'); //------------------DEBUGGING
				die; //прекратить выполнение текущего скрипта
			}
		}
		$templates = array(
				'note',
				'add'
		);
		$parameters = array(
			'header' => $header,
			'id' => $id,
			'task' => $taskList['task'],
			'color' => $taskList['color'],
			'font' => $taskList['font']
		);
		LoadingPages::view($templates, $parameters);
	}
}
?>