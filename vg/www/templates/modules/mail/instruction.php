1. Настройте файл /vg/system/defines.php

2. Вставьте в файл /index.html перед закрывающим тегом </body> код который подключит javascript
<script src="/vg/www/templates/modules/mail/js/mail.js"></script>

3. Надите вашу форму в шаблоне и добавьте в нее класс SendMail, убедитесь что стоит method="post", и что стоит на кнопке type="submit"

Пример формы

<form class="contact-form SendMail" method="post">
	<input type="hidden" name="type_name" value="Скрытое поле, здесь можно написать название формы или страницы, чтобы отличать разные форнмы">
	<input type="text" name="Имя" placeholder="Имя">
	<input type="email" name="email" placeholder="email">
	<button type="submit">Отправить</button>
</form>