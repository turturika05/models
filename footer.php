<?php

?>
<footer class="footer">
	<div class="footer-container">
		<div class="footer-column footer-column-1">
			<div class="footer-logo">
				<img src="img/logo_footer.png" alt="Лого">
			</div>
			<p class="footer-text">Наше видение заключается в том, чтобы обеспечить удобство и помочь увеличить ваш бизнес по
				продажам.</p>
		</div>
		<div class="footer-column">
			<ul class="footer-links">
				<li><a href="news.php">Новости</a></li>
				<li><a href="portfolio.php">Портфолио</a></li>
				<li><a href="casting.php">Кастинги</a></li>
			</ul>
		</div>
		<div class="footer-column">
			<ul class="footer-links">
				<li><a href="project.php">Проекты</a></li>
				<li><a href="contact.php">Контакты</a></li>
			</ul>
		</div>
		<div class="footer-column">
			<p class="footer-text" id="footer-text">Соц. сети</p>
			<div class="footer-icons">
				<a href="#"><img src="img/icon_footer_1.png"></a>
				<a href="#"><img src="img/icon_footer_2.png"></a>
				<a href="#"><img src="img/icon_footer_3.png"></a>
			</div>
		</div>
		<div class="footer-column footer-column-5">
			<ul class="footer-links">
				<li><a href="#">Политика конфиденциальности</a></li>
				<li><a href="#">Правила и условия</a></li>
			</ul>
		</div>
	</div>
	<div class="footer-bottom">
		<p>©2024 MODELS PRO. Все права защищены</p>
	</div>
</footer>


<div id="loginModal" class="modal">
	<div class="modal-content">
		<div class="modal-container">
			<div class="modal-image">
				<img src="img/form_image.png" alt="Изображение для формы входа">
			</div>
			<div class="modal-form">
				<span class="close" id="closeLoginModalBtn">&times;</span>
				<h2>Вход</h2>
				<p id="loginError" style="color:red; display:none;"></p>
				<form id="loginForm" method="post">
					<label for="login">Логин</label>
					<input type="text" id="login" name="login" placeholder="Введите email" required>

					<label for="password">Пароль</label>
					<input type="password" id="password" name="password" placeholder="Введите пароль" required>

					<div class="flex">
						<label class="switch">
							<input type="checkbox" id="rememberMe">
							<span class="slider round"></span>
						</label>
						<label for="rememberMe" class="label-text">Запомнить меня</label>
					</div>

					<!-- Google reCAPTCHA -->
					<div class="g-recaptcha" data-sitekey="6Lftzf4pAAAAAI7NNG87LKrbsmbtUR7RTZjJym-P"></div>

					<button type="submit">Войти</button>
				</form>
				<div class="signup-link">
					<p>У вас нет аккаунта? <a href="#" id="openRegisterModalBtn">Зарегистрироваться</a></p>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Модальное окно регистрации -->
<div id="registerModal" class="modal">
	<div class="modal-content">
		<div class="modal-container">
			<div class="modal-image">
				<img src="img/form_image.png" alt="Изображение для формы регистрации">
			</div>
			<div class="modal-form">
				<span class="close" id="closeRegisterModalBtn">&times;</span>
				<h2>Регистрация</h2>
				<p id="registerError" style="color:red; display:none;"></p>
				<form id="registerForm" method="post">
					<label for="reg-login">Логин</label>
					<input type="text" id="reg-login" name="reg-login" placeholder="Введите email" required>

					<label for="reg-password">Пароль</label>
					<input type="password" id="reg-password" name="reg-password" placeholder="Введите пароль" required>

					<div class="flex">
						<label class="switch">
							<input type="checkbox" id="reg-rememberMe">
							<span class="slider round"></span>
						</label>
						<label for="reg-rememberMe" class="label-text">Запомнить меня</label>
					</div>

					<button type="submit">Зарегистрироваться</button>
				</form>
				<div class="signup-link">
					<p>У вас уже есть аккаунт? <a href="#" id="openLoginModalFromRegister">Войти</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="main.js"></script>
</body>
</html>