	<div id="paper">
	  <span class="add" title="Регистрация" onclick="location.href='/note/registration';">+&#x21D2;</span>
	  <span class="add login" title="Вход" onclick="sendingformlogin('enter')">&#x21D2;</span>
	  <div id="pattern">
		<div id="content">
			<h1 class="center">Блокнот</h1>
			<div class="center">
				<br>
				<br>
				<h2 class="center">Вход</h2>
				<h3 class="label">Логин: </h3><input id="login" type="text" name="name" class="input-field">
				<br>
				<h3 class="label">Пароль: </h3><input id="password" type="password" name="password" class="input-field">
			</div>
		</div>
	  </div>
	</div>
	<div class="lds-back">
	<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</div>
</html>
<script src="/js/crypto-js/sha256.js"></script>
<script src="/js/crypto-js/sha1.js"></script>
<script src="/js/note.js"></script>
</body>