<?php

namespace App\Services;

use Telegram\Bot\Api;

class TelegramService
{
    private $telegram;

    // Constructor to initialize the Telegram API with the bot token
    public function __construct()
    {
        $config = include __DIR__ . '/../../config/config.php';
        $this->telegram = new Api($config['telegram_bot_token']);
    }

    // Method to get updates from the Telegram webhook
    public function getWebhookUpdates()
    {
        return $this->telegram->getWebhookUpdates();
    }

    // Method to send a message via the Telegram bot
    public function sendMessage($chatId, $text)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }

    // Public method to set the webhook for the Telegram bot
    public function setWebhook($url)
    {
        return $this->telegram->setWebhook(['url' => $url]);
    }
}