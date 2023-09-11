	<style type="text/css">
		#paper {
			box-shadow:
				/* Тень верхнего слоя */
				0 -1px 1px rgba(0,0,0,0.15),
				<?php 
					echo '/*Количество страниц: '.$parameters['pagesCount'].'*/';
					for($i = 0; $i < $parameters['pagesCount'] - 1; $i++) {
						echo '0 '.(-10*($i + 1)).'px 0 '.(-5*($i + 1)).'px #fff,';
						echo '0 '.(-10*($i + 1)).'px 1px '.(-4 - 5*$i).'px rgba(0,0,0,0.15),';
					}
				?>
				0 0 0 0 rgba(0,0,0,0)
		;}
		:root {}
	</style>
	<div id="paper">
	  <span class="add" title="Добавить заметку" onclick="location.href='/note/add';">+&#x270E;</span>
	  <span class="add login" title="<?php echo $_SESSION['login_note']; ?>" onclick="location.href='/note/settings';">&#x2261;</span>
	  <div id="pattern">
		<script src="/js/crypto-js/aes.js"></script>
		<div id="content">
			<?php 
				foreach($parameters['taskList'] as $row) {
					echo "<p title='".$row['date']." ".$row['time']."' style='font-family: ".$row['font']."; color: ".$row['color'].";'>
					<select class='select-check' title='Выбрать статус'>
						<option value='1' ";
						if($row['status'] == 1) echo 'selected';
						echo ">&#x25BA; новая</option>
						<option value='2' ";
						if($row['status'] == 2) echo 'selected';
						echo ">&#x2714; выполнено</option>
						<option value='3' ";
						if($row['status'] == 3) echo 'selected';
						echo ">&#x2718; не актуально</option>
						<option value='/".$row['id']."'>&#x270E; редактировать</option>
					</select>";
					echo $row['task'];
					echo '</p><br>';
					echo "<script>
						var tasktext = document.getElementsByTagName('p')[document.getElementsByTagName('p').length - 1];
						if(localStorage.getItem('password_note')){
							var bytes = CryptoJS.AES.decrypt(tasktext.lastChild.data, localStorage.getItem('password_note'));
							tasktext.lastChild.data = bytes.toString(CryptoJS.enc.Utf8);
						}else {
							tasktext.lastChild.data = 'Доступ закрыт';
						}
					</script>";
				}
			?>
		</div>
	  </div>
	  <span class="pages">
		<?php echo PaginationL::linkPagination($parameters['page'], $parameters['pagesCount']); ?>
	  </span>
	  <span class="page"><?php echo $parameters['page']; ?></span>
	</div>
	<div class="lds-back">
	<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</div>
</html>
<script src="/js/note.js"></script>
<script>
document.querySelector("#content").addEventListener("change", e => {
	if(e.target.value[0] === '/'){
		document.location.href = '/note/add' + e.target.value;
	}else {
		sendingform('status', e.target.value, e.target.options[e.target.options.length-1].value.slice(1));
	}
});
</script>
</body>