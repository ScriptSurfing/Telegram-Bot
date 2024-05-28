<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Bot;

// Instantiate the Bot class and handle incoming updates
$bot = new Bot();
$bot->handle();
