<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload dependencies
require __DIR__ . '/../vendor/autoload.php';

use App\Services\TelegramService;

try {
    // Load the configuration file
    $config = include __DIR__ . '/../config/config.php';
    
    if (!isset($config['telegram_bot_token'])) {
        throw new Exception('Telegram bot token is not set in the config file.');
    }
    
    // Initialize TelegramService
    $telegramService = new TelegramService();
    $response = $telegramService->setWebhook('https://botcoin.bot/telegram_bot/public/index.php');

    // Output the response
    echo '<pre>';
    var_dump($response);
    echo '</pre>';

} catch (Exception $e) {
    // Handle exceptions and display error messages
    echo 'Error: ' . $e->getMessage();
}