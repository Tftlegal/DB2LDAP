<?php
// /var/data/secrets/jmdb2ldap/ldap.php

// Определяем константы для подключения к LDAP
define('LDAP_SERVER', 'ldap.example.com'); // адрес LDAP-сервера
define('LDAP_PORT', '636'); // порт LDAP-сервера
define('LDAP_USERNAME', 'cn=admin,dc=example,dc=com'); // DN пользователя с правами на изменение
define('LDAP_PASSWORD', 'super-secret-password'); // пароль администратора
define('LDAP_BASE_USER', 'ou=users,dc=example,dc=com'); // базовый DN для пользователей
