<?
/* NEW_USER_CONFIRM */
$MESS["NEW_USER_CONFIRM_SUBJECT"] = "#SITE_NAME#: ������������� ����������� ������ ������������";
$MESS["NEW_USER_CONFIRM_TEXT"] = "�������������� ��������� ����� #SITE_NAME#
------------------------------------------

������������,

�� �������� ��� ���������, ��� ��� ��� ����� ��� ����������� ��� ����������� ������ ������������ �� ������� #SERVER_NAME#.

��� ��� ��� ������������� �����������: #CONFIRM_CODE#

��� ������������� ����������� ��������� �� ��������� ������:
http://#SERVER_NAME#/auth/confirm/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#

�� ����� ������ ������ ��� ��� ������������� ����������� �� ��������:
http://#SERVER_NAME#/auth/confirm/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#

��������! ��� ������� �� ����� ��������, ���� �� �� ����������� ���� �����������.

---------------------------------------------------------------------

��������� ������������� �������������.";

/* USER_PASS_REQUEST */
$MESS["USER_PASS_REQUEST_SUBJECT"] = "#SITE_NAME#: ������������� ����������� ������ ������������";
$MESS["USER_PASS_REQUEST_TEXT"] = "�������������� ��������� ����� #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

��� ����� ������ ��������� �� ��������� ������:
http://#SERVER_NAME#/auth/changepass/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

���� ��������������� ����������:

ID ������������: #USER_ID#
������ �������: #STATUS#
Login: #LOGIN#

��������� ������������� �������������.";

/* USER_INFO */
$MESS["USER_INFO_SUBJECT"] = "#SITE_NAME#: ��������������� ����������";
$MESS["USER_INFO_TEXT"] = "�������������� ��������� ����� #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

���� ��������������� ����������:

ID ������������: #USER_ID#
������ �������: #STATUS#
Login: #LOGIN#

�� ������ �������� ������, ������� �� ��������� ������:
http://#SERVER_NAME#/auth/changepass/?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

��������� ������������� �������������.";

/* USER_INVITE */
$MESS["USER_INFO_SUBJECT"] = "#SITE_NAME#: ����������� �� ����";
$MESS["USER_INFO_TEXT"] = "�������������� ��������� ����� #SITE_NAME#
------------------------------------------
������������, #NAME# #LAST_NAME#!

��������������� ����� �� ��������� � ����� ������������������ �������������.

���������� ��� �� ��� ����.

���� ��������������� ����������:

ID ������������: #ID#
Login: #LOGIN#

����������� ��� ������� ������������� ������������� ������.

��� ����� ������ ��������� �� ��������� ������:
http://#SERVER_NAME#/changepass/?change_password=yes&USER_LOGIN=#URL_LOGIN#&USER_CHECKWORD=#CHECKWORD#";

?>