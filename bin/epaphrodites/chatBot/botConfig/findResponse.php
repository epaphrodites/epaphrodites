<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Epaphrodites\epaphrodites\auth\session_auth;

trait findResponse
{

    /**
     * Finds the best response based on the user's input by calculating Jaccard coefficients.
     *
     * @param string $userMessage The message input by the user.
     * @return array The best-matching response.
     */
    private function getResponse(string $userMessage): array
    {
        // Keys for array elements
        $loginKey = 'login';
        $answersKey = 'answers';
        $questionKey = 'question';

        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);

        // Load questions and answers from a JSON file
        $questionsAnswers = $this->loadJsonFile();

        // Set default message
        $defaultMessage = $this->epaphroditesDefaultAnswers()[$answersKey];
        $defaultMessage = $defaultMessage[array_rand($defaultMessage)];

        // Set initial values and threshold
        $bestCoefficient = 0.3;
        $response = [];

        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {
            // Clean and normalize the question
            $questionWords = $this->cleanAndNormalize($question);

            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);

            // Update response based on similarity coefficient
            if ($coefficient >= $bestCoefficient && $coefficient > 0) {
                $bestCoefficient = $coefficient;
                $response = $associatedAnswer[$answersKey];
                $response = $response[array_rand($response)];
            }
        }

        // Get user connected login
        $login = (new session_auth)->login();
        $defaultUsers = [$loginKey => $login];

        // Prepare user question data
        $userQuestion = [$questionKey => $userMessage];

        // Construct final response array
        $bestAnswers = empty($response)
            ? array_merge($defaultUsers, $userQuestion, [$answersKey => $defaultMessage])
            : array_merge($defaultUsers, $userQuestion, [$answersKey => $response]);

        // Return the response with the highest similarity coefficient or default message
        return $bestAnswers;
    }

}
