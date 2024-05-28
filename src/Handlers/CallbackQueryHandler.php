<?php

namespace App\Handlers;

use Telegram\Bot\Objects\Update;
use App\Services\TelegramService;

class CallbackQueryHandler
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

    // Method to handle callback query updates from Telegram
    public function handle(Update $update)
    {
        try {
            $callbackQuery = $update->getCallbackQuery();
            $data = $callbackQuery->getData();
            $chatId = $callbackQuery->getMessage()->getChat()->getId();

            // Handle the callback query data
            switch ($data) {
                case 'option1':
                    $this->telegramService->sendMessage($chatId, "You selected option 1.");
                    break;
                case 'option2':
                    $this->telegramService->sendMessage($chatId, "You selected option 2.");
                    break;
                // Add more cases as needed
            }
            $this->log('Handled callback query: ' . $data);
        } catch (\Exception $e) {
            $this->log('Error handling callback query: ' . $e->getMessage());
        }
    }
}