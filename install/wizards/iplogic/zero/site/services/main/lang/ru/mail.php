<?
/* NEW_USER_CONFIRM */
$MESS["NEW_USER_CONFIRM_SUBJECT"] = "#SITE_NAME#: Подтверждение регистрации нового пользователя";
$MESS["NEW_USER_CONFIRM_TEXT"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------

Здравствуйте,

Вы получили это сообщение, так как ваш адрес был использован при регистрации нового пользователя на сервере #SERVER_NAME#.

Ваш код для подтверждения регистрации: #CONFIRM_CODE#

Для подтверждения регистрации перейдите по следующей ссылке:
http://#SERVER_NAME#/auth/?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#

Вы также можете ввести код для подтверждения регистрации на странице:
http://#SERVER_NAME#/auth/?confirm_registration=yes&confirm_user_id=#USER_ID#

Внимание! Ваш профиль не будет активным, пока вы не подтвердите свою регистрацию.

---------------------------------------------------------------------

Сообщение сгенерировано автоматически.";

/* USER_PASS_REQUEST */
$MESS["USER_PASS_REQUEST_SUBJECT"] = "#SITE_NAME#: Запрос на смену пароля пользователя";
$MESS["USER_PASS_REQUEST_TEXT"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Для смены пароля перейдите по следующей ссылке:
http://#SERVER_NAME#/auth/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Ваша регистрационная информация:

ID пользователя: #USER_ID#
Статус профиля: #STATUS#
Login: #LOGIN#

Сообщение сгенерировано автоматически.";

/* USER_INFO */
$MESS["USER_INFO_SUBJECT"] = "#SITE_NAME#: Регистрационная информация";
$MESS["USER_INFO_TEXT"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Ваша регистрационная информация:

ID пользователя: #USER_ID#
Статус профиля: #STATUS#
Login: #LOGIN#

Вы можете изменить пароль, перейдя по следующей ссылке:
http://#SERVER_NAME#/auth/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Сообщение сгенерировано автоматически.";

/* USER_INVITE */
$MESS["USER_INVITE_SUBJECT"] = "#SITE_NAME#: Приглашение на сайт";
$MESS["USER_INVITE_TEXT"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
Здравствуйте, #NAME# #LAST_NAME#!

Администратором сайта вы добавлены в число зарегистрированных пользователей.

Приглашаем Вас на наш сайт.

Ваша регистрационная информация:

ID пользователя: #ID#
Login: #LOGIN#

Рекомендуем вам сменить установленный автоматически пароль.

Для смены пароля перейдите по следующей ссылке:
http://#SERVER_NAME#/auth/?change_password=yes&USER_LOGIN=#URL_LOGIN#&USER_CHECKWORD=#CHECKWORD#";

?>