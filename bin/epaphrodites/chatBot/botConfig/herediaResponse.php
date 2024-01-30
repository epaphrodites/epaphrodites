<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Epaphrodites\epaphrodites\auth\session_auth;
use Epaphrodites\epaphrodites\chatBot\makeActions\botActions;

trait herediaResponse
{

    /**
     * Finds the best response based on the user's input by calculating Jaccard coefficients.
     *
     * @param string $userMessage The message input by the user.
     * @param string $jsonFiles json file path name.
     * @return array The best-matching response.
     */
    private function getHerediaResponse(string $userMessage , string $jsonFiles): array
    {
        
        $loginKey = 'login';
        $answersKey = 'answers';
        $actionsKey = 'actions';
        $contextKey = 'context';
        $questionKey = 'question';
        $coefficientKey = 'coefficient';
        $login = (new session_auth)->login();

        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);
        
        // Initialize variables to store the best coefficient and the response
        $response = [];
        $bestAnswers ='';
        $coefficient = 0;
        $defaultUsers = [];
        $makeAction ='none';
        $correctSentence ="";
        $defaultMessage = [];
        $bestCoefficient = 0;
        $mainCoefficient = 0.3;
        $temporaryResponses = [];

        // Load questions and answers from a JSON file
        $questionsAnswers = $this->loadJsonFile($jsonFiles);
        
        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {
            // Clean and normalize the question
            $questionWords = $this->cleanAndNormalize($question);
            
            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);

            // Check the best answers
            if ($coefficient >= 0.1) {
                $temporaryResponses[] = 
                [ 
                    $coefficientKey => $coefficient , 
                    $answersKey=>$associatedAnswer[$answersKey] ,
                    $actionsKey=>$associatedAnswer[$actionsKey],
                    $contextKey=>$associatedAnswer[$contextKey]
                ];
            }
        }

        // Select the top comments based on coefficient
        $commentsToConsider = array_slice($temporaryResponses, 0, min(count($temporaryResponses), 100));
        
        if (!empty($commentsToConsider)) {
            $maxComment = max($commentsToConsider);
        
            $bestCoefficient = $maxComment[$coefficientKey] ?? 0;
            $bestAnswers = $maxComment[$answersKey] ?? null;
            $makeAction = $maxComment[$actionsKey] ?? null;
            $correctSentence = $this->calculateContext($userMessage , $maxComment[$contextKey]) ?? null;
        }
        
        // Update the best coefficient and the corresponding response
        if ($bestCoefficient >= $mainCoefficient&&!empty($correctSentence)) {
            $mainCoefficient = $bestCoefficient;
            $response = $bestAnswers[array_rand($bestAnswers)];
            $makeAction == "none"&&$bestCoefficient>0.5 ? : (new botActions)->actions($makeAction , $login , $jsonFiles);
        } elseif ($bestCoefficient > 0.1) {
            $response = $this->needMoreAnswers()[$answersKey][array_rand($this->needMoreAnswers()[$answersKey])];
        }
        
        // If no response is found, get a default bot message
        if(empty($response)){

            $defaultMessage = $this->defaultAnswers()[$answersKey];
            $randomIndex = array_rand($defaultMessage);
            $defaultMessage = $defaultMessage[$randomIndex];

            $response = [ $answersKey => $defaultMessage ];
        }else{
            $response = [ $answersKey => $response ];
        }
        
        // Get user login and question
        $defaultUsers = [ $loginKey => $login ];
        $userQuestion = [ $questionKey => $userMessage ];

        // Merge all information to form the final response
        $result =  array_merge( $defaultUsers , $userQuestion , $response );

        // Return the response with the highest similarity coefficient
        return $result;
    }    
}
