<?php

namespace Epaphrodites\epaphrodites\chatBot;

trait findResponse
{

    /**
     * Finds the best response based on the user's input by calculating Jaccard coefficients.
     *
     * @param string $userMessage The message input by the user.
     * @return string The best-matching response.
     */
    private function getResponse(string $userMessage): string
    {
        // Clean and normalize the user's message
        $userWords = $this->cleanAndNormalize($userMessage);

        // Initialize variables to store the best coefficient and the response
        $bestCoefficient = 0;
        $response = '';

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

        // Return the response with the highest similarity coefficient
        return !empty($response) ? $response : "";
    }
}
