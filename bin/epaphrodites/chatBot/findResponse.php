<?php

namespace Epaphrodites\epaphrodites\chatBot;
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
        
        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);
        
        // Initialize variables to store the best coefficient and the response
        $bestCoefficient = 0;
        $response = [];
        $defaultMessage = [];

        // Load questions and answers from a JSON file
        $questionsAnswers = $this->loadJsonFile();
        
        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {
            // Clean and normalize the question
            $questionWords = $this->cleanAndNormalize($question);

            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);

            // Update the best coefficient and the corresponding response
            if ($coefficient > $bestCoefficient) {
                $bestCoefficient = $coefficient;
                $response = $associatedAnswer;
            }
        }

        $login = (new session_auth)->login();

        // Get bot default messages
        $defaultMessage = [ 
            'answers' => "I am a work assistance AI. I do not handle this kind of information." , 
            'type' => "txt",
        ];

        // Get user connected login
        $defaultUsers = [ 'login' => $login ];

        $userQuestion = [ 'question' => $userMessage ];

        $result = !empty($response) ? array_merge( $defaultUsers , $userQuestion , $response ) : array_merge( $defaultUsers , $userQuestion , $defaultMessage);

        // Return the response with the highest similarity coefficient
        return $result;
    }
}