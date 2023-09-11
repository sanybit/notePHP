	<div id="paper">
	  <span class="add" title="Назад" onclick="location.href='/note';">&#8617;</span>
	  <span class="add login" title="Выход из аккаунта" onclick="sendingformlogin('exit');">&#x2716;</span>
	  <div id="pattern">
		<div id="content">
			<h1 class="center" >Настройки</h1>
			<div class="center" style="width: 330px;">
				<h3 class="label"><span id="login"><?php echo @$_SESSION['login_note']; ?></span>
				<span class="add" title="Удалить аккаунт" onclick="sendingformlogin('del_account');">Удалить</span>
				<br>
					Дата регистрации: <?php echo $parameters['date_reg']; ?><br>
					IP: <?php echo $parameters['ip']; ?><br>
					Дата входа: <?php echo $parameters['date_last']; ?><br>
					Текущий IP: <?php echo $_SERVER['REMOTE_ADDR']; ?>
				</h3>
				<br>
				<h3 class="label">Email: </h3><input id="email" type="email" name="email" class="input-field" value="<?php echo $parameters['email']; ?>">
				<span class="add" title="Сменить почту" onclick="sendingformlogin('save_email');">&#10004;</span>
				<br>
				<span class="comment">
					(При смене почты потребуется повторная активация)
				</span>
				<br>
				<h3 class="label">Telegram: </h3><input id="telegram" type="text" name="telegram" class="input-field" value="<?php echo $parameters['telegram']; ?>">
				<span class="add" title="Сменить Telegram" onclick="sendingformlogin('save_telegram');">&#10004;</span>
				<br>
				<h3 class="label">Текущий пароль: </h3><input id="password_current" type="password" name="password" class="input-field">
				<br>
				<h3 class="label">Новый пароль: </h3><input id="password_new" type="password" name="password" class="input-field">
				<span class="add" title="Сменить пароль" onclick="sendingformlogin('save_password');">&#10004;</span>
				<br>
				<span class="comment">
					(При смене пароля старые заметки станут не доступны и потребуется повторная авторизация)
				</span>
				<br>
				<h3 class="label">Повтор пароля: </h3><input id="password_new_repeat" type="password" name="password" class="input-field">
				<br>
			</div>
		</div>
	  </div>
	</div>
	<div class="lds-back">
	<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</div>
</html>
<script src="/js/crypto-js/sha256.js"></script>
<script src="/js/note.js"></script>
</body>