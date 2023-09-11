	<div id="paper">
	  <span class="add" title="Назад" onclick="location.href='/note/login';">&#8617;</span>
	  <span class="add login" title="Зарегистрироваться" onclick="sendingformlogin('register')">&#x21D2;</span>
	  <div id="pattern">
		<div id="content">
			<h1 class="center">Блокнот</h1>
			<div class="center">
				<br>
				<br>
				<br>
				<br>
				<br>
				<h2 class="center">Регистрация</h2>
				<h3 class="label">*Логин: </h3><input id="login" type="text" name="name" class="input-field"></input>
				<br>
				<h3 class="label">*Email: </h3><input id="email" type="email" name="email" class="input-field"></input>
				<br>
				<h3 class="label">Telegram: </h3><input id="telegram" type="text" name="telegram" class="input-field"></input>
				<br>
				<h3 class="label">*Пароль: </h3><input id="password" type="password" name="password" class="input-field"></input>
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