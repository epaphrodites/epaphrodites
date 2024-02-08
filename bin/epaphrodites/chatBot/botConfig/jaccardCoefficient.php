<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Normalizer;

trait jaccardCoefficient
{

    /**
     * Calculates the Jaccard coefficient between two arrays.
     *
     * @param array $questionArray The first array.
     * @param array $AnswersArray The second array.
     * @return float The Jaccard coefficient value.
     */
    private function calculateJaccardCoefficient(array $initQuestionArray, array $AnswersArray): float
    {

        // Convert arrays to sets to remove duplicates
        $AnswersArray = array_unique($AnswersArray);
        $initQuestionArray = array_unique($initQuestionArray);        

        $mainKeyword = $this->extractBracketValues($AnswersArray);

        $othersKeyword = $this->extractBracesValues($AnswersArray);

        $questionArray = $this->filterUsersQuestion( $initQuestionArray , array_merge( $othersKeyword , $mainKeyword ));
        
        // Calculate the weighted intersection of the two arrays 
        $intersection = $this->countSimilarWords($questionArray , $AnswersArray);
        
        // Calculate the weighted intersection of the main keyword and the AnswersArray
        (int) $mainKeywordIntersec = $this->getMainKeyCoefficient($mainKeyword , $initQuestionArray);
        
        // Calculate the weighted intersection of the main keyword and the AnswersArray
        (int) $otherKeywordIntersec = $this->getMainKeyCoefficient($othersKeyword , $initQuestionArray);
    
        $mainKeywordCoefficient = $mainKeywordIntersec * 0.27;

        $otherKeywordCoefficient = $otherKeywordIntersec * 0.47;

        // Calculate the size of the union of the two arrays (using AnswersArray as reference)
        $union = !empty($mainKeyword)&&count($questionArray)!==count($initQuestionArray) ? count($AnswersArray)-1 : count($AnswersArray);

        // Calculate the Jaccard coefficient
        $jaccardCoefficient = ($union !== 0) ? $intersection / $union : 0;

        $jaccardCoefficient = $mainKeywordCoefficient + $jaccardCoefficient + $otherKeywordCoefficient;

        return $jaccardCoefficient;
    }
   

    private function getMainKeyCoefficient( $mainKeyword , $initQuestionArray ):int
    {
        $intersection = $this->countSimilarWords( $initQuestionArray , $mainKeyword);

        $intersection = $intersection > 0 ? 1 : 0;

        return $intersection;
    }

    /**
     * @param string $question
     * @param array $answers
     * @return null|string
     */
    private function calculateContext(array $questions = [], array $answers = []): ?string 
    {

      (string) $question = implode(' ', $questions);

      return array_reduce($answers, fn($found, $answer) => $found ?: str_contains(strtolower($question), $answer), null);
    }

    /**
     * @param array $botAnswers
     * @return array
    */
    private function extractBracketValues(array $botAnswers):array{

        
        $extractedValues = [];
        $pattern = '/\[(.*?)\]/';
        $botAnswersString = implode(' ', $botAnswers);
    
        preg_match_all($pattern, $botAnswersString, $matches);
    
        foreach ($matches[1] as $match) {
            $values = explode(',', $match);
            foreach ($values as $value) {
                if (!empty($value)) {
                    $extractedValues[] = $value;
                }
            }
        }
        $arrayToRemove = implode(' ' , $extractedValues);
        $extractedValues = explode(' ' , $arrayToRemove);
        
        return $extractedValues;
    }

    /**
     * @param array $botAnswers
     * @return array
    */
    private function extractBracesValues(array $botAnswers):array{

        
        $extractedValues = [];
        $pattern = '/\{(.*?)\}/';
        $botAnswersString = implode(' ', $botAnswers);
    
        preg_match_all($pattern, $botAnswersString, $matches);
    
        foreach ($matches[1] as $match) {
            $values = explode(',', $match);
            foreach ($values as $value) {
                if (!empty($value)) {
                    $extractedValues[] = $value;
                }
            }
        }
        $arrayToRemove = implode(' ' , $extractedValues);
        $extractedValues = explode(' ' , $arrayToRemove);
        
        return $extractedValues;
    }    

    /**
     * @param array $initQuestionArray
     * @param array $arrayToRemove
     * @return array
    */
    private function filterUsersQuestion(array $initQuestionArray , array $arrayToRemove): array
    {
        
        return array_diff($initQuestionArray, $arrayToRemove);
    }

    /**
     * @param array $questions
     * @param array $answers
     * @return int
    */
    private function countSimilarWords(array $questions, array $answers): int
    {
        $totalSimilarWords = 0;
    
        foreach ($questions as $question) {
            $questionWords = $this->normalizeWords($question);

            foreach ($answers as $answer) {
                $answerWords = $this->normalizeWords($answer);

                $totalSimilarWords += $this->verifySimilarWords($questionWords, $answerWords , 1);
            }
        }

        return $totalSimilarWords;
    }
    
    /**
     * @param string $sentence
     * @return array
    */    
    private function normalizeWords(string $sentence): array
    {
        // Remove punctuation and convert to lowercase
        $sentence = strtolower(preg_replace('/[^\p{L}\s]+/u', '', $sentence));
        
        // Remove trailing 's' from words
        $sentence = preg_replace('/\b(\w+)s\b/', '$1', $sentence);
    
        // Normalize Unicode characters to decomposed form
        $sentence = Normalizer::normalize($sentence, Normalizer::FORM_D);
    
        // Remove diacritics
        $sentence = preg_replace('/\p{M}/u', '', $sentence);
    
        // Split sentence into words
        return explode(' ', $sentence);
    }
    
    /**
     * @param string $questionWords
     * @param string $answerWords
     * @return int 
    */    
    private function wordSimilarity(string $questionWords, string $answerWords):int {

        return levenshtein($questionWords, $answerWords);
    }
    
    /**
     * @param array $questionWords
     * @param array $answerWords
     * @param int $threshold
     * @return int 
    */    
    private function verifySimilarWords( array $questionWords, array $answerWords, int $threshold):int {
        $count = 0;
        foreach ($questionWords as $questionWords) {
            foreach ($answerWords as $answerWords) {
                if ($this->wordSimilarity($questionWords, $answerWords) <= $threshold) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }
}
