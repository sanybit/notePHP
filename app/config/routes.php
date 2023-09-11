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
	'(.*)' => '404', // любая строка
);
?>
