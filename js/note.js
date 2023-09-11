
function sendingform(button, status = 's', id = 0) { //функция отправки формы для добавления заметок
	
	let formData = new FormData(); //создаём форму
	
	if(button == 'status') { //при выборе статуса
		//Наполняем поля формы
		formData.set("id", id);
		formData.set("status", status);
		formData.set('button', 'status');
	}
	
	if(button == 'save') { //при нажатии кнопки сохранить
		//Наполняем поля формы
		formData.set("id", document.getElementById('task_id').value);
		formData.set("font", document.getElementById('font-select').value);
		formData.set('color', document.getElementById('color-select').value);
		let plaintext = document.getElementById('task-body').innerText;
		if(!plaintext) {
			alert('Заметка не может быть пустой');
			return;
		}
		// Шифрование
		let ciphertext;
		if(localStorage.getItem('password_note')) {
			ciphertext = CryptoJS.AES.encrypt(plaintext, localStorage.getItem('password_note')).toString();
		}else {
			if(!confirm('Пароль пуст, заметка не будет зашифрована. Продолжить?')) return;
			ciphertext = plaintext;
		}
		formData.set('task', ciphertext);
		formData.set('button', 'save');
	}
	
	if(button == 'del') { //при нажатии кнопки Удалить
		if(confirm("Удалить заметку")) { //если пользователь подтвердил удаление
			//Наполняем поля формы
			formData.set("id", document.getElementById('task_id').value);
			formData.set('button', 'del');
		}else {
			return;
		}
	}
	
	//Создаём и отправляем запрос на сервер
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/note/add");
	xhr.timeout = 15000; //время ожидания
	xhr.send(formData);
	document.querySelector('.lds-back').style.setProperty('display', 'block'); //затемняем экран
	
	//Проверяем статус запроса
	xhr.onload = function() {
	  document.querySelector('.lds-back').style.setProperty('display', 'none');
	  if (xhr.status != 200) { // анализируем HTTP-статус ответа, если статус не 200, то произошла ошибка
		alert(`Ошибка ${xhr.status}: ${xhr.statusText}`); // Например, 404: Not Found
	  } else { // если всё прошло гладко, выводим результат
		alert(xhr.response); // response -- это ответ сервера
		
		//Если было удаление то перебрасываем не страницу /add
		if(xhr.response == 'Заметка удалена') document.location.href = '/note/add';
		
		//Если было сохранение то переходим на данную заметку
		if(xhr.response.split('N')[0] == 'Заметка сохранена c номером ') document.location.href = '/note/add/' + xhr.response.split('N')[1];
	  }
	};
	xhr.ontimeout = function() {
		// время ожидания запроса истекло.
		alert('Сервер не отвечает');
		document.querySelector('.lds-back').style.setProperty('display', 'none');
	}; 
}

function sendingformlogin(button) { //функция отправки формы для логин
	
	let formData = new FormData(); //создаём форму
	let password;
	
	//Создаём запрос на сервер
	let xhr = new XMLHttpRequest();
	
	if(button == 'register') { //при нажатии кнопки зарегестрироваться
		//Наполняем поля формы
		formData.set('login', document.getElementById('login').value);
		password = document.getElementById('password').value;
		if(password.length < 8) {
			alert('Пароль не менее 8 символов');
			return;
		}
		formData.set('password', CryptoJS.SHA256(password));
		formData.set('email', document.getElementById('email').value);
		formData.set('telegram', document.getElementById('telegram').value);
		formData.set('button', 'register');
		
		xhr.open("POST", "/note/registration");
	}
	
	if(button == 'save_email') { //при нажатии кнопки сохранить почту
		if(!confirm('Сменить почту?')) return;
		//Наполняем поля формы
		formData.set('login', document.getElementById('login').innerText);
		formData.set('email', document.getElementById('email').value);
		formData.set('button', 'save_email');
		
		xhr.open("POST", "/note/settings");
	}
	
	if(button == 'save_telegram') { //при нажатии кнопки сохранить телеграм
		if(!confirm('Сменить Telegram?')) return;
		//Наполняем поля формы
		formData.set('login', document.getElementById('login').innerText);
		formData.set('telegram', document.getElementById('telegram').value);
		formData.set('button', 'save_telegram');
		
		xhr.open("POST", "/note/settings");
	}
	
	if(button == 'save_password') { //при нажатии кнопки сменить пароль
		//Наполняем поля формы
		formData.set('login', document.getElementById('login').innerText);
		let password_current = document.getElementById('password_current').value;
		let password_new = document.getElementById('password_new').value;
		let password_new_repeat = document.getElementById('password_new_repeat').value;
		if((password_current.length < 8) || (password_new.length < 8) || (password_new_repeat.length < 8)) {
			alert('Пароль не менее 8 символов');
			return;
		}
		if(!(password_new == password_new_repeat)) {
			alert('Новый пароль и повтор не совпадают');
			return;
		}          
		formData.set('password_current', CryptoJS.SHA256(password_current));
		formData.set('password_new', CryptoJS.SHA256(password_new));
		formData.set('button', 'save_password');
		
		xhr.open("POST", "/note/settings");
	}
	
	if(button == 'del_account') { //при нажатии кнопки удалить аккаунт
		//Наполняем поля формы
		if(confirm('Вы точно хотите удалить аккаунт?')) {
			if(password = prompt('Введите пароль')) {
				formData.set('login', document.getElementById('login').innerText);
				formData.set('password', CryptoJS.SHA256(password));
				formData.set('button', 'del_account');
				xhr.open("POST", "/note/settings");
			}else return;
		}else return;
	}
	
	if(button == 'enter') { //при нажатии кнопки вход
		//Наполняем поля формы
		formData.set('login', document.getElementById('login').value);
		password = document.getElementById('password').value;
		if(password.length < 8) {
			alert('Пароль не менее 8 символов');
			return;
		}
		formData.set('password', CryptoJS.SHA256(password));
		formData.set('button', 'enter');
		
		xhr.open("POST", "/note/login");
	}
	
	if(button == 'exit') { //при нажатии кнопки выход
		if(confirm('Выйти из аккаунта?')) {
			//Наполняем поля формы
			formData.set('button', 'exit');
			xhr.open("POST", "/note/login");
		}else return;
	}
	
	xhr.timeout = 15000; //время ожидания
	xhr.send(formData);
	document.querySelector('.lds-back').style.setProperty('display', 'block');
	
	//Проверяем статус запроса
	xhr.onload = function() {
	  document.querySelector('.lds-back').style.setProperty('display', 'none');
	  if (xhr.status != 200) { // анализируем HTTP-статус ответа, если статус не 200, то произошла ошибка
		alert(`Ошибка ${xhr.status}: ${xhr.statusText}`); // Например, 404: Not Found
	  } else { // если всё прошло гладко, выводим результат
		alert(xhr.response); // response -- это ответ сервера
		if(xhr.response == 'Вход выполнен') {
			localStorage.setItem('password_note', CryptoJS.SHA1(password));
			document.location.href = '/note';
		}
		if((xhr.response == 'Выход выполнен') || (xhr.response == 'Аккаунт удалён') || (xhr.response == 'Почта сохранена') || (xhr.response == 'Пароль сохранён')) {
			localStorage.removeItem('password_note');
			document.location.href = '/note/login';
		}
		if(xhr.response == 'Готово, осталось подтвердить почту') {
			document.location.href = '/note/login';
		}
	  }
	};
	xhr.ontimeout = function() {
		// время ожидания запроса истекло.
		alert('Сервер не отвечает');
		document.querySelector('.lds-back').style.setProperty('display', 'none');
	}; 
}
