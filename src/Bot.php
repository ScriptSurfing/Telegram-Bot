<?php

namespace App;

use App\Services\TelegramService;
use App\Handlers\CommandHandler;
use App\Handlers\MessageHandler;
use App\Handlers\CallbackQueryHandler;

class Bot
{
    private $telegramService;
    private $commandHandler;
    private $messageHandler;
    private $callbackQueryHandler;
    private $logFile;
    private $rateLimitStore;

    // Constructor to initialize services, handlers, log file, and rate limit store
    public function __construct()
    {
        $this->telegramService = new TelegramService();
        $this->commandHandler = new CommandHandler();
        $this->messageHandler = new MessageHandler();
        $this->callbackQueryHandler = new CallbackQueryHandler();
        $this->logFile = __DIR__ . '/../logs/bot.log';
        $this->rateLimitStore = []; // Initialize the rate limit store
    }

    // Method to log messages
    private function log($message)
    {
        $date = new \DateTime();
        file_put_contents($this->logFile, $date->format('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }

    // Method to check rate limiting
    private function isRateLimited($userId)
    {
        $now = time();
        $windowSize = 60; // 1 minute
        $maxRequests = 10; // Maximum 10 requests per minute

        // Initialize the rate limit array for the user if not set
        if (!isset($this->rateLimitStore[$userId])) {
            $this->rateLimitStore[$userId] = [];
        }

        // Log current state
        $this->log("Rate limiting check for user {$userId}. Current request count: " . count($this->rateLimitStore[$userId]));

        // Remove outdated timestamps
        $this->rateLimitStore[$userId] = array_filter(
            $this->rateLimitStore[$userId],
            function ($timestamp) use ($now, $windowSize) {
                return ($now - $timestamp) < $windowSize;
            }
        );

        // Log filtered state
        $this->log("After filtering outdated timestamps, request count: " . count($this->rateLimitStore[$userId]));

        // Check if the user is rate-limited
        if (count($this->rateLimitStore[$userId]) >= $maxRequests) {
            $this->log("User {$userId} is rate-limited.");
            return true;
        }

        // Add the current timestamp to the store
        $this->rateLimitStore[$userId][] = $now;
        $this->log("User {$userId} request added. New request count: " . count($this->rateLimitStore[$userId]));

        return false;
    }

    // Method to handle incoming updates from Telegram
    public function handle()
    {
        try {
            // Get the update from Telegram webhook
            $update = $this->telegramService->getWebhookUpdates();
            $this->log('Received update: ' . print_r($update, true));

            if ($update->getMessage()) {
                $userId = $update->getMessage()->getFrom()->getId();

                // Check rate limiting
                if ($this->isRateLimited($userId)) {
                    $this->telegramService->sendMessage($userId, "You are sending messages too quickly. Please slow down.");
                    return;
                }

                if ($update->getMessage()->getText()[0] === '/') {
                    // Handle command updates
                    $this->commandHandler->handle($update);
                } else {
                    // Handle message updates
                    $this->messageHandler->handle($update);
                }
            } elseif ($update->has('callback_query')) {
                // Handle callback query updates
                $this->callbackQueryHandler->handle($update);
            }
        } catch (\Exception $e) {
            $this->log('Error: ' . $e->getMessage());
        }
    }
}