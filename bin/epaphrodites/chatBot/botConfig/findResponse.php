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
        
        $loginKey = 'login';
        $answersKey = 'answers';
        $questionKey = 'question';

        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);
        
        // Initialize variables to store the best coefficient and the response
        $response = [];
        $defaultUsers = [];
        $defaultMessage = [];
        $bestCoefficient = 0.3;

        // Load questions and answers from a JSON file
        $questionsAnswers = $this->loadJsonFile();
        
        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {
            // Clean and normalize the question
            $questionWords = $this->cleanAndNormalize($question);

            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);
            
            // Update the best coefficient and the corresponding response
            if ($coefficient >= $bestCoefficient) {
                $bestCoefficient = $coefficient;
                $response = $associatedAnswer[$answersKey];
                $randomIndex = array_rand($response);
                $response = $response[$randomIndex];
            }

            if ($coefficient > 0.1 && $coefficient < $bestCoefficient) {
                $needMoreInfos = $this->needMoreAnswers()[$answersKey];
                $response = $needMoreInfos[array_rand($needMoreInfos)];
            }  
        }
        
        $login = (new session_auth)->login();

        if(empty($response)){

            // Get bot default messages
            $defaultMessage = $this->epaphroditesDefaultAnswers()[$answersKey];
            $randomIndex = array_rand($defaultMessage);
            $defaultMessage = $defaultMessage[$randomIndex];

            $defaultMessage = [ $answersKey => $defaultMessage ];
        }else{
            $response = [ $answersKey => $response ];
        }

        // Get user connected login
        $defaultUsers = [ $loginKey => $login ];

        $userQuestion = [ $questionKey => $userMessage ];

        $result = !empty($response) ? array_merge( $defaultUsers , $userQuestion , $response ) : array_merge( $defaultUsers , $userQuestion , $defaultMessage);

        // Return the response with the highest similarity coefficient
        return $result;
    }
}
