<?php

// Подключение классов
require_once('system/modules/Control/Config.php');
require_once('system/modules/Control/Core.php');
require_once('system/modules/Control/Engine.php');
require_once('system/modules/Control/ErrorSupervisor.php');

$errorController = new \Control\ErrorSupervisor();

\Control\Core::init();

// Инициализация
$engine = new \Control\Engine();        // Start the System
$engine->init();                        // Init modules entities
$engine->parsing();                     // Parsing input data
$engine->routing();                     // Find any routes
$engine->controlling();                 // Do something usefull
$engine->theming();                     // Put data into template engine and wraping the data in the theme
$engine->display();                     // Display output data