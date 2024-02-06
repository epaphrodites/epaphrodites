<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Epaphrodites\epaphrodites\auth\session_auth;
use Epaphrodites\epaphrodites\chatBot\makeActions\botActions;
use Epaphrodites\epaphrodites\chatBot\defaultAnswers\mainEpaphroditesDefaultMessages;

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

        // Initialize variables to store the best coefficient and the response
        $response = [];
        $bestAnswers ='';
        $coefficient = 0;
        $maxComment = null;
        $defaultUsers = [];
        $makeAction ='none';
        $correctSentence ="";
        $defaultMessage = [];
        $bestCoefficient = 0;
        $mainCoefficient = 0.3;
        $questionsAnswers = [];
        $temporaryResponses = [];
        
        $loginKey = 'login';
        $answersKey = 'answers';
        $actionsKey = 'actions';
        $contextKey = 'context';
        $defaultLanguage = 'eng';
        $questionKey = 'question';
        $languageKey = 'language';
        $coefficientKey = 'coefficient';
        $login = (new session_auth)->login();

        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);
        
        // Load questions and answers from a JSON file
        $questionsAnswers = $this->loadJsonFile();

        // Detect last language
        $lastLanguage = $this->detectLastLang($login);

        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {

            // Clean and normalize the question
            $questionWords = $this->splitTextIntoWords($question);
            
            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);

            // Check the best answers
            if ($coefficient >= 0.1) {
                $temporaryResponses[] = 
                [ 
                    $coefficientKey => $coefficient , 
                    $answersKey=>$associatedAnswer[$answersKey] ,
                    $actionsKey=>$associatedAnswer[$actionsKey],
                    $contextKey=>$associatedAnswer[$contextKey],
                    $languageKey=>$associatedAnswer[$languageKey]
                ];
            }
        }

        // Select the top comments based on coefficient
        $commentsToConsider = array_slice($temporaryResponses, 0, min(count($temporaryResponses), 100));

        if (!empty($commentsToConsider)) {

            foreach ($commentsToConsider as $checkTheBestAnswers) {

                if ($maxComment === null || $checkTheBestAnswers[$coefficientKey] > $maxComment[$coefficientKey]) {

                    $maxComment = $checkTheBestAnswers;
                }
            }

            $bestCoefficient = $maxComment[$coefficientKey] ?? 0;
            $bestAnswers = $maxComment[$answersKey] ?? null;
            $makeAction = $maxComment[$actionsKey] ?? null;
            $defaultLanguage = $maxComment[$languageKey];
            $correctSentence = $this->calculateContext($userWords , $maxComment[$contextKey]) ?? null;
        }

        // Update the best coefficient and the corresponding response
        if ($bestCoefficient >= $mainCoefficient&&!empty($correctSentence)) {

            $mainCoefficient = $bestCoefficient;
            $response = $this->answersChanging($bestAnswers);
            $makeAction == "none"&&$bestCoefficient>=0.5 ? : (new botActions)->defaultActions($makeAction , $login);
            
        } elseif ($bestCoefficient > 0.1) {

            $getContent = $this->epaphroditesDefaultMessageToGetMorePrecision($lastLanguage , $this->getMainClass() );
            
            $getAnswers = $getContent[$answersKey];
            $defaultLanguage = $getContent[$languageKey];
            $response = $this->answersChanging($getAnswers);
        }
        
        // If no response is found, get a default bot message
        if(empty($response)){

            $getContent = $this->epaphroditesDefaultMessageWhereNoResult($lastLanguage , $this->getMainClass() );
            
            $getAnswers = $getContent[$answersKey];
            $defaultLanguage = $getContent[$languageKey];
            $defaultMessage = $this->answersChanging($getAnswers);
            $response = [ $answersKey => $defaultMessage ];
        }else{
            $response = [ $answersKey => $response ];
        }
        
        // Get user login and question
        $defaultUsers = [ $loginKey => $login ];
        $userQuestion = [ $questionKey => $userMessage ];
        $userLanguage = [ $languageKey => $defaultLanguage ];

        // Merge all information to form the final response
        $result =  array_merge( $defaultUsers , $userQuestion , $response , $userLanguage );

        // Return the response with the highest similarity coefficient
        return $result;
    }

    /**
     * @return \Epaphrodites\epaphrodites\chatBot\defaultAnswers\mainEpaphroditesDefaultMessages
     */
    private function getMainClass():object
    {
        return new mainEpaphroditesDefaultMessages;
    }
}
