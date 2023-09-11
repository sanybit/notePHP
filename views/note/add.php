	<style type="text/css">:root {}</style>
	<div id="paper">
	  <span class="add" title="Назад" onclick="location.href='/note';">&#8617;</span>
	  <span class="add login" title="Сохранить" onclick="sendingform('save')">&#x21D2;</span>
	  <span class="add del" title="Удалить" onclick="sendingform('del')">&#x2716;</span>
	  <div id="pattern">
		<div id="content">
			<br>
			<h1 class="center">Блокнот</h1>
			<div class="center">
				<br>
				<br>
				<h2 class="center new"><?php echo $parameters['header']; ?></h2>
				<hr>
				<br>
				<span>Шрифт: </span>
				<select id="font-select" class="select-font" title="Выбрать шрифт" >
					<option value="brushtype-normal" <?php if($parameters['font'] == 'brushtype-normal') echo 'selected'; ?> >brushtype-normal</option>
					<option value="isadora-m-bold" <?php if($parameters['font'] == 'isadora-m-bold') echo 'selected'; ?> >isadora-m-bold</option>
					<option value="olgactt" <?php if($parameters['font'] == 'olgactt') echo 'selected'; ?> >olgactt</option>
				</select>
				<br>
				<span>Цвет: </span>
				<input id="color-select" type="color" name="font_color" value="<?php echo $parameters['color']; ?>" class="select-color">
			</div>
			<br>
			<input id="task_id" type="hidden" value="<?php echo $parameters['id'];?>">
			<?php if($parameters['task'] != '') {
					echo "<div id='task-body' contenteditable='true' class='task' style='color: ".$parameters['color'].";'>".$parameters['task']."</div>";
				}else {
					echo "<div id='task-body' contenteditable='true' class='task' data-placeholder='Написать заметку'></div>";
				}
			?>
		</div>
	  </div>
	</div>
	<div class="lds-back">
	<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</div>
</html>
<script src="/js/crypto-js/aes.js"></script>
<script src="/js/note.js"></script>
<script>
var fontSelect = document.getElementById("font-select");
changeOption();

var tasktext = document.getElementById('task-body');
if(localStorage.getItem('password_note')) {
	var bytes = CryptoJS.AES.decrypt(tasktext.innerText, localStorage.getItem('password_note')); //расшифровываем текст
	tasktext.innerText = bytes.toString(CryptoJS.enc.Utf8); //кладём обратно текст заметки
}else {
	tasktext.innerText = 'Доступ к заметке закрыт';
}

function changeOption(){ //меняем шрифт
	
    var selectedOption = fontSelect.options[fontSelect.selectedIndex];
	document.querySelector(':root').style.setProperty('--font', selectedOption.value);
	
}
fontSelect.addEventListener("change", changeOption); //Слушатель выбора шрифта

document.getElementById("color-select").addEventListener('input', function(ev) { //Слушатель выбора цвета
	document.getElementById("task-body").style.color = document.getElementById("color-select").value; //меняем цвет
});
</script>
</body>