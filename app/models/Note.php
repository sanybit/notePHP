<?php
class Note
{
	// Метод проверки наличия записи в таблице
	public static function checkingTask($id) {
		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		$result = $db->query('SELECT COUNT(*) FROM `tasktable` WHERE id = '.$id); //получаем количество записей
		$count = $result->fetchColumn(); //получаем из объекта число
		if($count) return true;
		else return false;
	}
	
	// Метод проверки наличия пользователя в таблице
	public static function checkingLogin($login = false, $email = false, $activation = false) {
		
		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		
		if($login == false) {
			$result = $db->query("SELECT COUNT(*) FROM `usertable` WHERE email = '".$email."'"); //получаем количество записей
			$count = $result->fetchColumn(); //получаем из объекта число
			if($count) return true;
			else return false;
		}
		$existence = false;
		$result = $db->query("SELECT COUNT(*) FROM `usertable` WHERE login = '".$login."'"); //получаем количество записей
		$count = $result->fetchColumn(); //получаем из объекта число
		if($count) {
			
			$existence = true;
			
			if($email || $activation) {
				$result = $db->query("SELECT * FROM usertable WHERE login='".$login."'");
				$result->setFetchMode(PDO::FETCH_ASSOC); //оставит индексы в виде названий
				$user = $result->fetch();
				
				if($email && $user['email'] == $email) $existence = true;
				else $existence = false;
			
				if($activation && $user['activation'] == 'active') $existence = true;
			}
		}
		
		return $existence;
	}
	
	// Метод входа по паролю
	public static function enterLogin($login, $password) {
		$login = htmlspecialchars($login);
		$password = htmlspecialchars($password);

		if(!Note::checkingLogin($login)) {
			return 'Пользователь не существует';
		}
		if(!Note::checkingLogin($login, false, true)) {
			return 'Пользователь не активирован';
		}
		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		$result = $db->query("SELECT * FROM usertable WHERE login='".$login."'");
		$result->setFetchMode(PDO::FETCH_ASSOC); //оставит индексы в виде названий
		$user = $result->fetch();
		if($user['password'] == $password) {
			$_SESSION['login_note'] = $login;
			$db->query("UPDATE usertable SET date_last = '".date("Y-m-d")."' WHERE login = '".$login."'");
			return 'Вход выполнен';
		}else {
			return 'Пароль не верный';
		}
	}
	
	// Метод удаления пользователя в таблице
	public static function deleteUser($login) {	
		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		// Описываем нужный запрос к базе данных
		$sql = 'DELETE FROM `usertable` WHERE `login` = ?';
		// Подготавливаем SQL запрос (зашита от инъекций)
		$query = $db->prepare($sql);  
		// Выполняем запрос к базе
		if ($query->execute([$login])) {
			$sql = 'DELETE FROM `tasktable` WHERE `login` = ?';
			$query = $db->prepare($sql);
			if($query->execute([$login])) return 'Аккаунт удалён';
			else return 'Аккаунт удалён не полностью';		
		}else return 'Не удалось удалить аккаунт';
	}
	
	// Метод активации
	public static function performActivation($activation) {
		$activation = htmlspecialchars($activation);
		$db = Db::getConnection(); //получаем объект класса PDO из класса Db	
		$result = $db->query("SELECT COUNT(*) FROM `usertable` WHERE activation = '".$activation."'"); //получаем количество записей
		$count = $result->fetchColumn(); //получаем из объекта число
		if($count == 1) {
			$db->query("UPDATE usertable SET activation = 'active' WHERE activation = '".$activation."'");
			return 'Активация выполнена';
		}else {
			return 'Ошибка активации или аккаунт уже активирован';
		}
	}
	
	// Метод возвращает одну задачу по индификатору в запросе ($id)
	public static function getTaskItemById($id) {
		$id = intval($id); //возвращаем целое значение переменной
		if ($id){
			$db = Db::getConnection(); //получаем объект класса PDO из класса Db
			$result = $db->query('SELECT * FROM tasktable WHERE id='.$id);
			$result->setFetchMode(PDO::FETCH_ASSOC); //оставит индексы в виде названий
			$taskItem = $result->fetch();
			return $taskItem;
		}
	}
	
	// Метод возвращает пользователя по логину
	public static function getUserItemByLogin() {
		$login = $_SESSION['login_note'];
		if ($login){
			$db = Db::getConnection(); //получаем объект класса PDO из класса Db
			$result = $db->query("SELECT * FROM usertable WHERE login='".$login."'");
			$result->setFetchMode(PDO::FETCH_ASSOC); //оставит индексы в виде названий
			$loginItem = $result->fetch();
			return $loginItem;
		}
	}

	// Метод возвращает список задач
	public static function getTaskList($count_rows = 1, $sorting = 'id', $page = 0) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		$taskList = array();
		
		// Делаем запрос в запросе к базе чтобы отсортировать конечный результат
		// Делаем выборку для страници 3 записи и в ней сортируем по заданному полю
		//$result = $db->query("SELECT * FROM (SELECT * FROM list ORDER BY id LIMIT $count_rows OFFSET $page) a ORDER BY $sorting");
		$result = $db->query("SELECT * FROM tasktable WHERE login = '".$_SESSION['login_note']."' ORDER BY $sorting LIMIT $count_rows OFFSET $page");
		
		$result->setFetchMode(PDO::FETCH_ASSOC); //оставит индексы ввиде названий
		
		// В цикле обращаемся к методу fetch() объекта в переменной $result
		// при этом в цикле мы будем получать доступ к переменной $row,
		// которая символизирует строку из БД
		// (При работе с PDO - используется Объектно-Ориентированный Подход)
		// В цикле мы записываем необходимые полученные данные в массив результата
		// и далее, возвращаем этот массив: return $newsList
		$i = 0;
		while($row = $result->fetch()) {
			foreach($row as $name => $res) {
				$taskList[$i][$name] = $res;
			}
			$i++ ;
		}
		return $taskList ;
	}
	
	// Метод возвращает количество записей в таблице
	public static function getCountList() {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db

		// Описываем нужный запрос к базе данных
		$result = $db->query("SELECT COUNT(*) FROM `tasktable` WHERE login = '".$_SESSION['login_note']."'"); //получаем количество всех записей
		$count = $result->fetchColumn(); //получаем из объекта число
		
		return $count;
	}
	
	// Метод изменяет статус заметки
	public static function editStatus($task_array) {

		$id = intval($task_array['id']); //возвращаем целое значение переменной
		$status = intval($task_array['status']); //возвращаем целое значение переменной
		
		$row = Note::getTaskItemById($id);
		
		if($_SESSION['login_note'] == $row['login']) { //проверяем доступ пользователя к заметке
			
			$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		
			// Описываем нужный запрос к базе данных
			$sql = "UPDATE tasktable SET status = :status WHERE id=".$id;
			// Подготавливаем SQL запрос (зашита от инъекций)
			$query = $db->prepare($sql);
			  
			// Выполняем запрос к базе
			if ($query->execute(['status' => $status])) {
				return 'Статус сохранён';
			}else return 'Не удалось изменить статус';
		}
		return 'Доступ запрещён';
	}
	
	// Метод добавления и изменения записи в таблице
	public static function addTask($task_array) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		
		// Преобразуем специальные символы в HTML-сущности
		$id = intval($task_array['id']); //возвращаем целое значение переменной
		$login = $_SESSION['login_note'];
		$status = 1;
		$task = htmlspecialchars($task_array['task']);
		$date = date("Y-m-d");
		$time = date("H:i:s"); 
		$color = htmlspecialchars($task_array['color']);
		$font = htmlspecialchars($task_array['font']);
		
		if($id == 0) {
			// Описываем нужный запрос к базе данных
			$sql = "INSERT INTO tasktable(login, status, task, date, time, color, font) VALUES(:login, :status, :task, :date, :time, :color, :font)";
			
			// Подготавливаем SQL запрос (зашита от инъекций)
			$query = $db->prepare($sql);
			  
			// Выполняем запрос к базе
			if ($query->execute(['login' => $login, 'status' => $status, 'task' => $task, 'date' => $date, 'time' => $time, 'color' => $color, 'font' => $font])) {
				$result = $db->query("SELECT LAST_INSERT_ID()");
				$count = $result->fetchColumn(); //получаем из объекта число
				return 'Заметка сохранена c номером N'.$count;
				//return 'Заметка сохранена';
			}else return 'Не удалось сохранить заметку';
		}
		
		if($id > 0) {
			
			if(!Note::checkingTask($id)) {
				return 'Заметка не существует';
			}

			// Описываем нужный запрос к базе данных
			$sql = "UPDATE tasktable SET task = :task, color = :color, font = :font WHERE id=".$id;

			// Подготавливаем SQL запрос (зашита от инъекций)
			$query = $db->prepare($sql);
			  
			// Выполняем запрос к базе
			if ($query->execute(['task' => $task, 'color' => $color, 'font' => $font])) {
				return 'Заметка изменена';
			}else return 'Не удалось изменить заметку';
		}
	}
	
	// Метод удаления записи в таблице
	public static function deleteTask($id) {
		// Преобразуем специальные символы в HTML-сущности
		$id = intval($id); //возвращаем целое значение переменной
		
		$row = Note::getTaskItemById($id);
		
		if($_SESSION['login_note'] == $row['login']) { //проверяем доступ пользователя к заметке
		
			$db = Db::getConnection(); //получаем объект класса PDO из класса Db
			
			if(!Note::checkingTask($id)) {
				return 'Заметка не существует';
			}
			
			// Описываем нужный запрос к базе данных
			$sql = 'DELETE FROM `tasktable` WHERE `id` = ?';

			// Подготавливаем SQL запрос (зашита от инъекций)
			$query = $db->prepare($sql);
			  
			// Выполняем запрос к базе
			if ($query->execute([$id])) {
				return 'Заметка удалена';
			}else return 'Не удалось удалить заметку';
		}
		return 'Доступ запрещён';
	}
	
	// Метод добавления и изменения логинов
	public static function addLogin($task_array) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		
		// Преобразуем специальные символы в HTML-сущности
		$login = htmlspecialchars($task_array['login']);
		$password = htmlspecialchars($task_array['password']);
		$email = htmlspecialchars($task_array['email']);
		$telegram = htmlspecialchars($task_array['telegram']);
		$date_last = date("Y-m-d");
		$date_reg = date("Y-m-d");
		$ip = $_SERVER['REMOTE_ADDR'];
		$activation = hash('sha256', $login.date("His"));
		
		if(Note::checkingLogin($login)) {
			return 'Пользователь уже существует';
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return 'Неправильная почта';
		}
		if(Note::checkingLogin(false, $email)) {
			return 'Почта уже используется';
		}
		
		// Описываем нужный запрос к базе данных
		$sql = "INSERT INTO usertable(login, password, email, telegram, date_last, date_reg, ip, activation) VALUES(:login, :password, :email, :telegram, :date_last, :date_reg, :ip, :activation)";
		
		// Подготавливаем SQL запрос (зашита от инъекций)
		$query = $db->prepare($sql);
		  
		// Выполняем запрос к базе
		if ($query->execute(['login' => $login, 'password' => $password, 'email' => $email, 'telegram' => $telegram, 'date_last' => $date_last, 'date_reg' => $date_reg, 'ip' => $ip, 'activation' => $activation])) {
			Note::sendEmail($email, $activation);
			return 'Готово, осталось подтвердить почту';
		}else return 'Ошибка регистрации';
	}
	
	// Метод изменения email
	public static function saveEmail($email) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		// Преобразуем специальные символы в HTML-сущности
		$email = htmlspecialchars($email);
		if(Note::checkingLogin(false, $email)) {
			return 'Почта уже используется';
		}
		$activation = hash('sha256', $_SESSION['login_note'].date("His"));
		// Описываем нужный запрос к базе данных
		$sql = "UPDATE usertable SET email = :email, activation = :activation WHERE login='".$_SESSION['login_note']."'";
		// Подготавливаем SQL запрос (зашита от инъекций)
		$query = $db->prepare($sql);
		// Выполняем запрос к базе
		if ($query->execute(['email' => $email, 'activation' => $activation])) {
			Note::sendEmail($email, $activation);
			return 'Почта сохранена';
		}else return 'Не удалось сохранить почту';
	}
	
	// Метод изменения telegram
	public static function saveTelegram($telegram) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		// Преобразуем специальные символы в HTML-сущности
		$telegram = htmlspecialchars($telegram);
		$sql = "UPDATE usertable SET telegram = :telegram WHERE login='".$_SESSION['login_note']."'";
		// Подготавливаем SQL запрос (зашита от инъекций)
		$query = $db->prepare($sql);
		// Выполняем запрос к базе
		if ($query->execute(['telegram' => $telegram])) {
			return 'Telegram сохранён';
		}else return 'Не удалось сохранить Telegram';
	}
	
	// Метод изменения пароля
	public static function savePassword($password_current, $password_new) {

		$db = Db::getConnection(); //получаем объект класса PDO из класса Db
		// Преобразуем специальные символы в HTML-сущности
		$password_current = htmlspecialchars($password_current);
		$password_new = htmlspecialchars($password_new);
		
		$user = Note::getUserItemByLogin();
		if($user['password'] != $password_current) return 'Не верный текущий пароль';
		
		$sql = "UPDATE usertable SET password = :password WHERE login='".$_SESSION['login_note']."'";
		// Подготавливаем SQL запрос (зашита от инъекций)
		$query = $db->prepare($sql);
		// Выполняем запрос к базе
		if ($query->execute(['password' => $password_new])) {
			$_SESSION['login_note'] = false;
			return 'Пароль сохранён';
		}else return 'Не удалось сохранить пароль';
	}
	
	// Метод отправки писма на email
	public static function sendEmail($to = 'test@mail', $url = '123456789') {

		// тема письма
		$subject = 'Активация в Note';

		// текст письма
		$message = "
		<html>
		<head>
		  <title>Активация в блокноте</title>
		</head>
		<body>
		  <p>Для активации перейдите по ссылке <a href='http://note/registration/$url' target='_blank'>Активация</a></p>
		</body>
		</html>
		";

		// Для отправки HTML-письма должен быть установлен заголовок Content-type
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Отправляем
		return mail($to, $subject, $message, $headers);
	}
}
?>
