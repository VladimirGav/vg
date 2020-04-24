# Отправка писем с формы обратной связи на почту администратора по SMTP

При работе использует php, html, js. Может работать на любых самописных сайтах, сайтах на CMS и в обычных html шаблонах. Исходники используют composer и phpmailer.

## Инструкция

### Посмотреть на [YouTube](https://www.youtube.com/watch?v=YlpkDVUyhVA)

  - Скачайте архив [https://github.com/VladimiravGav/vg](https://github.com/VladimiravGav/vg)
  - Распакуйте архив на свой сайт, папка "vg" должна находиться в корне сайта, где находится файл index.html или index.php
  - Настройте файл [/vg/system/defines.php](https://github.com/VladimiravGav/vg/system/defines.php) , в нем необходимо указать данные для SMTP подключения и указать e-mail администратора, на который будут приходить письма
  - Вставьте в файл index.html, index.php или в шаблон вашего сайта, перед закрывающимся тегом /body код который подключит скрипт
```html
<script src="/vg/www/templates/modules/mail/js/mail.js"></script>
```
  - Найдите вашу форму в шаблоне и добавьте в нее класс SendMail, убедитесь что стоит method="post" enctype="multipart/form-data", и что на кнопке присутствует type="submit"
### Пример готовой формы
```html
<form class="SendMail" method="post" enctype="multipart/form-data">
	<input type="hidden" name="type_name" value="Скрытое поле, здесь можно написать название формы или страницы, чтобы отличать разные формы">
	<input type="text" name="Имя" placeholder="Имя">
	<input type="email" name="email" placeholder="Почта">
	<input type="file" name="file" value="Файл">
	<button type="submit">Отправить сообщение</button>
</form>
```
## Как добавить reCAPTCHA
### Посмотреть на [YouTube](https://www.youtube.com/watch?v=YlpkDVUyhVA)
  - Обновите архив vg [https://github.com/VladimiravGav/vg](https://github.com/VladimiravGav/vg)
  - Перейдите в [google recaptcha](https://www.google.com/recaptcha/admin/create) и создайте reCAPTCHA v2 для вашего домена, сохраните публичный и приватный ключи капчи
  - Настройте файл [/vg/system/defines.php](https://github.com/VladimiravGav/vg/system/defines.php) , в нем необходимо включить '_RECAPTCHA_'(true) и указать '_PRIVATE_KEY_'(приватный ключ)
  - Добавьте скрипт google recaptcha
  ```html
  <script src="https://www.google.com/recaptcha/api.js"></script>
  ```
  - Добавьте в форму блок и задайте data-sitekey="ваш рекапча ключ сайта"
```html
<div class="g-recaptcha" data-sitekey="ваш рекапча ключ сайта"></div>
```
### Пример готовой формы c капчей
```html
<form class="SendMail" method="post" enctype="multipart/form-data">
	<input type="hidden" name="type_name" value="Скрытое поле, здесь можно написать название формы или страницы, чтобы отличать разные формы">
	<input type="text" name="Имя" placeholder="Имя">
	<input type="email" name="email" placeholder="Почта">
	<input type="file" name="file" value="Файл">
	<div class="g-recaptcha" data-sitekey="ваш рекапча ключ сайта"></div>
	<button type="submit">Отправить сообщение</button>
</form>
```


