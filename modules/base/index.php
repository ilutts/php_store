<?php
/**
 * Базовые общие настройки PHP
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');

ini_set('session.gc_maxlifetime', 60 * 20);
ini_set('session.cookie_lifetime', 60 * 20);

session_name('session_id');

session_start();
header('Content-Type: text/html; charset=utf-8');

include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

require($_SERVER['DOCUMENT_ROOT'] . '/modules/base/functions.php');