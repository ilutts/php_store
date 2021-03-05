<?php 

require($_SERVER['DOCUMENT_ROOT'] . '/modules/base/index.php');
require($_SERVER['DOCUMENT_ROOT'] . '/admin/modules/auth.php');

// Шапка сайта
require($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');

// Main
require($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . getAdminPage($menuHeader));

//Подвал
require($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); 

