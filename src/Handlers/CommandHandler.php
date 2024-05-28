<?php

namespace App\Handlers;

use Telegram\Bot\Objects\Update;
use App\Services\TelegramService;

class CommandHandler
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

    // Method to handle command updates from Telegram
    public function handle(Update $update)
    {
        try {
            // Get the command and its arguments
            $command = explode(' ', $update->getMessage()->getText())[0]; // Only get the command part

            // Respond to the command
            switch ($command) {
                case '/start':
                    // Respond to /start command
                    $this->telegramService->sendMessage($update->getMessage()->getChat()->getId(), "Welcome! You have successfully started the bot.");
                    break;
                // Add other command cases as needed
                default:
                    $this->telegramService->sendMessage($update->getMessage()->getChat()->getId(), "Unknown command: " . $command);
                    break;
            }
            $this->log('Handled command: ' . $command);
        } catch (\Exception $e) {
            $this->log('Error handling command: ' . $e->getMessage());
        }
    }
}
/*
<?php

namespace App\Handlers;

use Telegram\Bot\Objects\Update;
use App\Services\TelegramService;

class CommandHandler
{
    private $telegramService;

    public function __construct()
    {
        $this->telegramService = new TelegramService();
    }

    // Method to handle command updates from Telegram
    public function handle(Update $update)
    {
        // Get the command and its arguments
        $command = $update->getMessage()->getText();

        // Respond to the command
        switch ($command) {
            case '/start':
                // Respond to /start command
                $this->telegramService->sendMessage($update->getMessage()->getChat()->getId(), "Welcome! You have successfully started the bot.");
                break;
            // Add other command cases as needed
        }
    }
}
*/