<?php
// Маршруты
return array (
	'^$' => 'portfolio/index', // пустая строка
	'note/page/([0-9]+)' => 'note/index/$1',
	'note/add/([0-9]+)' => 'note/add/$1',
	'note/add' => 'note/add/$1',
	'note/login' => 'note/login',
	'note/settings' => 'note/settings',
	'note/registration/([0-9a-fA-F]{64})' => 'note/registration/$1',
	'note/registration' => 'note/registration/$1',
	'note' => 'note/index',
	'task/edit/([0-9]+)' => 'task/view/$1',
	'task/([0-9]+)' => 'task/index/$1',
	'task/enter' => 'login/enter',
	'task/add' => 'task/add',
	'task' => 'task/index',
	'(.*)' => '404', // любая строка
);
// 'task' - строка запроса
// 'task/index' - имя контроллера и экшена для обработки этого запроса (путь обработчика)
?>