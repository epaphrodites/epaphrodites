<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Epaphrodites\epaphrodites\auth\session_auth;
use Epaphrodites\epaphrodites\chatBot\makeActions\botActions;
use Epaphrodites\epaphrodites\chatBot\defaultAnswers\mainHerediaDefaultMessages;

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

        // Initialize variables to store the best coefficient and the response
        $response = [];
        $bestAnswers ='';
        $coefficient = 0;
        $maxComment = null;
        $defaultUsers = [];
        $makeAction ='none';
        $previous = false;
        $similarySentence ="";
        $defaultMessage = [];
        $bestCoefficient = 0;
        $mainCoefficient = 0.3;
        $questionsAnswers = [];
        $temporaryResponses = [];
        
        $botKey = 'key';
        $nameKey = 'name';
        $dateKey = 'date';
        $loginKey = 'login';
        $contextKey = 'context';
        $answersKey = 'answers';
        $actionsKey = 'actions';
        $defaultLanguage = 'eng';
        $previousKey = 'previous';
        $questionKey = 'question';
        $assemblyKey = 'assembly';
        $languageKey = 'language';
        $similarlyKey = 'similarly';
        $coefficientKey = 'coefficient';
        $login = (new session_auth)->login();

        // Get last answers previous is true
        $previousQuestion = $this->lastUsersQuestion($login , $jsonFiles);

        // Get last question previous is true
        $previousQuestion = !is_null($previousQuestion) ? $previousQuestion['question'] : "";

        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize("{$previousQuestion} {$userMessage}");

        // Detect user language
        $mainLanguage = $this->detectMainLanguage("{$previousQuestion} {$userMessage}" , $login , $jsonFiles);

        // Load questions and answers from a JSON file
        $questionsAnswers = $this->getContenAccordingLanguage($mainLanguage , $jsonFiles);

        // Iterate through each question and its associated answer
        foreach ($questionsAnswers as $question => $associatedAnswer) {

            // Clean and normalize the question
            $questionWords = $this->splitTextIntoWords($associatedAnswer["key"]);
            
            // Calculate the Jaccard coefficient between user input and each question
            $coefficient = $this->calculateJaccardCoefficient($userWords, $questionWords);

            // Check the best answers
            if ($coefficient >= 0.1) {
                $temporaryResponses[] = 
                [ 
                    $coefficientKey => $coefficient , 
                    $answersKey=>$associatedAnswer[$answersKey] ,
                    $actionsKey=>$associatedAnswer[$actionsKey],
                    $similarlyKey=>$associatedAnswer[$similarlyKey],
                    $nameKey=>$associatedAnswer[$nameKey],
                    $botKey=>$associatedAnswer[$botKey],
                    $contextKey=>$associatedAnswer[$contextKey],
                    $assemblyKey=>$associatedAnswer[$assemblyKey],
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
            $makeAction = $maxComment[$actionsKey] ?? null;
            $defaultLanguage = $maxComment[$languageKey];
            $similarySentence = $this->calculateSimilarWords($userWords , $maxComment[$similarlyKey]) ?? null;
            $bestAnswers = $this->assemblyWords( $userWords, $maxComment[$assemblyKey] , $maxComment[$nameKey] , $maxComment[$botKey] , $maxComment[$contextKey] , $maxComment[$answersKey] , $maxComment[$similarlyKey] );
        }
        
        // Update the best coefficient and the corresponding response
        if ($bestCoefficient >= $mainCoefficient&&$similarySentence>0) {

            $mainCoefficient = $bestCoefficient;
            $response = $bestAnswers;
            $makeAction == "none"&&$bestCoefficient>=0.5 ? : (new botActions)->actions($makeAction , $login , $jsonFiles);
            
        } elseif ($bestCoefficient > 0.1) {

            $previous = true;
            $getContent = $this->herediaDefaultMessageToGetMorePrecision($mainLanguage , $this->getClass() );
            
            $getAnswers = $getContent[$answersKey];
            $defaultLanguage = $getContent[$languageKey];
            $response = $this->answersChanging($getAnswers);
        }
        
        // If no response is found, get a default bot message
        if(empty($response)){

            $getContent = $this->herediaDefaultMessageWhereNoResult($mainLanguage , $this->getClass() );
            
            $getAnswers = $getContent[$answersKey];
            $defaultLanguage = $getContent[$languageKey];
            $defaultMessage = $this->answersChanging($getAnswers);
            $response = [ $answersKey => $defaultMessage ];
        }else{
            $response = [ $answersKey => $response ];
        }
        
        // Get user login and question
        $defaultUsers = [ $loginKey => $login ];
        $defaultPrevious = [ $previousKey => $previous ];
        $userQuestion = [ $questionKey => $userMessage ];
        $userLanguage = [ $languageKey => $defaultLanguage ];
        $defaultDateTime = [ $dateKey => date("d-y-Y h:i:s") ];

        // Merge all information to form the final response
        $result =  array_merge( $defaultDateTime , $defaultUsers , $userQuestion , $response , $userLanguage , $defaultPrevious );

        // Return the response with the highest similarity coefficient
        return $result;
    }

    /** 
     * @return \Epaphrodites\epaphrodites\chatBot\defaultAnswers\mainHerediaDefaultMessages
    */
    private function getClass():object
    {
        return new mainHerediaDefaultMessages;
    }
}
