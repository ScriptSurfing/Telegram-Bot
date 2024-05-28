<?php

namespace App\Services;

class UserService
{
    private $dbService;

    // Constructor to initialize the database service
    public function __construct()
    {
        $this->dbService = new DatabaseService(); // Create a new instance of DatabaseService
    }

    // Method to check if a user already exists in the database
    public function userExists($telegramId)
    {
        // Execute a query to check if a user with the given telegram_id exists
        $result = $this->dbService->executeQuery(
            "SELECT * FROM users WHERE telegram_id = ?",
            [$telegramId], // Parameter to bind to the query
            "i" // Type of the parameter ('i' for integer)
        );
        // Return true if the user exists (num_rows > 0), false otherwise
        return $result->num_rows > 0;
    }

    // Method to insert a new user into the database
    public function addUser($telegramId, $firstName, $lastName, $username, $languageCode)
    {
        // Sanitize user inputs to prevent XSS
        $sanitizedFirstName = htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8');
        $sanitizedLastName = htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8');
        $sanitizedUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $sanitizedLanguageCode = htmlspecialchars($languageCode, ENT_QUOTES, 'UTF-8');

        // Execute an insert query to add the user to the database
        $this->dbService->executeQuery(
            "INSERT INTO users (telegram_id, first_name, last_name, username, language_code) VALUES (?, ?, ?, ?, ?)",
            [$telegramId, $sanitizedFirstName, $sanitizedLastName, $sanitizedUsername, $sanitizedLanguageCode], // Parameters to bind to the query
            "issss" // Types of the parameters ('i' for integer, 's' for string)
        );
    }

    // Method to close the database connection
    public function close()
    {
        $this->dbService->close(); // Close the database connection
    }
}