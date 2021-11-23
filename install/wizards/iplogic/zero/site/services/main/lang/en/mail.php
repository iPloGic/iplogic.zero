<?
/* NEW_USER_CONFIRM */
$MESS["NEW_USER_CONFIRM_SUBJECT"] = "#SITE_NAME#: Confirmation of new user registration";
$MESS["NEW_USER_CONFIRM_TEXT"] = "Informational site message #SITE_NAME#
------------------------------------------

Hello,

You received this message because your address was used to register a new user on the #SERVER_NAME# server.

Your code to confirm registration: #CONFIRM_CODE#

To confirm your registration, go to the following link:
http://#SERVER_NAME#/auth/?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#

You can also enter the code to confirm registration on the page:
http://#SERVER_NAME#/auth/?confirm_registration=yes&confirm_user_id=#USER_ID#

Attention! Your profile will not be active until you confirm your registration.

---------------------------------------------------------------------

The message is generated automatically.";

/* USER_PASS_REQUEST */
$MESS["USER_PASS_REQUEST_SUBJECT"] = "#SITE_NAME#: Request to change user password";
$MESS["USER_PASS_REQUEST_TEXT"] = "Informational site message #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

To change your password, follow the link below:
http://#SERVER_NAME#/auth/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Your registration information:

User ID: #USER_ID#
Profile status: #STATUS#
Login: #LOGIN#

The message is generated automatically.";

/* USER_INFO */
$MESS["USER_INFO_SUBJECT"] = "#SITE_NAME#: Registration information";
$MESS["USER_INFO_TEXT"] = "Informational site message #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Your registration information:

User ID: #USER_ID#
Profile status: #STATUS#
Login: #LOGIN#

You can change your password by visiting the following link:
http://#SERVER_NAME#/auth/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

The message is generated automatically.";

/* USER_INVITE */
$MESS["USER_INVITE_SUBJECT"] = "#SITE_NAME#: Website invitation";
$MESS["USER_INVITE_TEXT"] = "Informational site message #SITE_NAME#
------------------------------------------
Hello #NAME# #LAST_NAME#!

By the site administrator, you have been added to the number of registered users.

We invite you to our website.

Your registration information:

User ID: #ID#
Login: #LOGIN#

We recommend that you change the password set automatically.

To change your password, follow the link below:
http://#SERVER_NAME#/auth/?change_password=yes&USER_LOGIN=#URL_LOGIN#&USER_CHECKWORD=#CHECKWORD#";

?>