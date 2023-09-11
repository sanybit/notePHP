<?php
class LoadingPages
{
	public static function view($templates, $parameters) {
		foreach($templates as $i => $_templates) {
			if($i == 0){
				if(file_exists(ROOT.'/views/'.$_templates.'/index.php')) { //проверяет существование указанного файла
					include(ROOT.'/views/'.$_templates.'/index.php'); //подключаем файл
				}else {
					Router::ErrorPage404('Главный шаблон вида не найден'); //------------------DEBUGGING
					break; //выходим из цикла
				}
			}else {
				if(file_exists(ROOT.'/views/'.$templates[0].'/'.$_templates.'.php')) { //проверяет существование указанного файла
					include(ROOT.'/views/'.$templates[0].'/'.$_templates.'.php'); //подключаем файл
				}
			}
		}
	}
}
?>