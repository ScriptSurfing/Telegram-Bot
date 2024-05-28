<?php

namespace App\Handlers;

use Telegram\Bot\Objects\Update;
use App\Services\TelegramService;

class MessageHandler
{
    private $telegramService;

    public function __construct()
    {
        $this->telegramService = new TelegramService();
    }

    // Method to log messages
    private function log($message)
    {
        $logFile = __DIR__ . '/../../logs/bot.log';
        $date = new \DateTime();
        file_put_contents($logFile, $date->format('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }

    // Method to handle message updates from Telegram
    public function handle(Update $update)
    {
        try {
            // Get the message text
            $message = $update->getMessage()->getText();
            $chatId = $update->getMessage()->getChat()->getId();

            // Respond to the message
            if ($message && $message[0] !== '/') {
                $this->telegramService->sendMessage($chatId, "You said: " . $message);
            }
            $this->log('Handled message: ' . $message);
        } catch (\Exception $e) {
            $this->log('Error handling message: ' . $e->getMessage());
        }
    }
}