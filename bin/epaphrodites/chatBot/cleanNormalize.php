<?php

namespace Epaphrodites\epaphrodites\chatBot;

trait cleanNormalize
{

    /**
     * Cleans and normalizes a text by removing special characters and converting to lowercase,
     * then splits it into an array of words.
     *
     * @param string $text The input text to be cleaned and normalized.
     * @return array An array containing the cleaned and normalized words.
     */
    public function cleanAndNormalize(string $text): array
    {
        // Convert the text to lowercase and remove special characters using regex
        $text = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));

        // Split the cleaned text into an array of words
        return explode(" ", $text);
    }
}
