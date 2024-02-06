<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait cleanNormalize
{
    /**
     * Cleans and normalizes a text by removing special characters and converting to lowercase,
     * then splits it into an array of words.
     *
     * @param string $text The input text to be cleaned and normalized.
     * @return array An array containing the cleaned and normalized words.
     */
    private function cleanAndNormalize(string $text): array
    {
        $cleanText = $this->cleanText($text);
        return $this->splitTextIntoWords($cleanText);
    }

    /**
     * Cleans the text by removing special characters and converting to lowercase.
     *
     * @param string $text The text to clean.
     * @return string The cleaned text.
     */
    private function cleanText(string $text): string
    {
        return strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));
    }

    /**
     * Splits the cleaned text into an array of words.
     *
     * @param string $cleanText The cleaned text.
     * @return array An array containing the words.
     */
    private function splitTextIntoWords(string $cleanText): array
    {
        $arrayDatas = explode(" ", $cleanText);

        return $this->filterWords($arrayDatas);
    }

    /**
     * Filters out common words from the array of words.
     *
     * @param array $words The array of words to filter.
     * @return array The filtered array of words.
     */
    private function filterWords(array $words): array
    {
        $wordsToRemove = $this->getWordsToRemove();
        return array_diff($words, $wordsToRemove);
    }

    /**
     * Get the common words to remove from the text.
     *
     * @return array The array of common words to remove.
     */
    private function getWordsToRemove(): array
    {
        return 
        [
            'le', 'la', 'les', 'des', 'une', 'un', 'l\'', 'a', 'à', 'ce', 'cette', 'ces', 'celui', 'celle', 'ceux', 'celles', 'un', 
            'une', 'des', 'du', 'de la', 'de l\'', 'de', 'la', 'le', 'les', 'leur', 'leurs', 'lui', 'eux', 'elle', 'elles', 'on', 'moi', 'toi', 
            'soi', 'nous', 'vous', 'se', 'me', 'te', 'lui', 'leur', 'y', 'en', 'qui', 'que', 'quoi', 'dont', 'où', 'quand', 'comment', 'combien',
            'the', 'a', 'an', 'this', 'that', 'these', 'those', 'some', 'any', 'each', 'every', 'my', 'your', 'his', 'her', 'its', 
            'our', 'their', 'whose', 'which', 'whichever', 'whatever', 'who', 'whom', 'whosever', 'whomever', 'whatever', 'somebody', 
            'someone', 'something', 'anybody', 'anyone', 'anything', 'nobody', 'none', 'no one', 'nothing', 'everybody', 'everyone', 'everything'
        ];
    }
}
