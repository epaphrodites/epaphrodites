<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use Normalizer;

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
        return $this->splitTextIntoWords($this->wordNormalizer($cleanText));
    }

    /**
     * Cleans the text by removing special characters and converting to lowercase.
     *
     * @param string $text The text to clean.
     * @return string The cleaned text.
     */
    private function cleanText(string $text): string
    {
        $cleanedText = preg_replace("/(?<=\s|^)'(\w+)/", '$1', $text);
        return strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $cleanedText));
    }
    
    /**
     * @param string $word
     * @return string
     */
    private function wordNormalizer(string $word):string {
    
        foreach ($this->letterTranslate() as $caractere => $equivalent) 
        {
            $word = str_replace($caractere, $equivalent, $word);
        }
    
        return $word;
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
        return array_merge( $this->frenchWord() , $this->englishWord());
    }

    /**
     * @return array
    */    
    private function frenchWord():array
    {
        return 
        [
            'le', 'la', 'les', 'des', 'une', 'un', 'l\'', 'a', 'ce', 'cette', 'ces', 'celui', 'celle', 'ceux', 'celles', 'un', 'sur', 'es' , 'est' , 'sont' , 'sommes',
            'une', 'des', 'du', 'de la', 'de l\'', 'de', 'la', 'le', 'les', 'leur', 'leurs', 'lui', 'eux', 'elle', 'elles', 'on', 'moi' ,'je' , 'toi', 'tu', 
            'soi', 'nous', 'vous', 'se', 'me', 'te', 'lui', 'leur', 'y', 'en', 'qui', 'que', 'quoi', 'dont', 'où', 'quand', 'comment', 'combien',
        ];
    }

    /**
     * @return array
    */
    private function englishWord():array
    {   
        return [
            'the', 'a', 'an', 'this', 'that', 'these', 'those', 'some', 'any', 'each', 'every', 'my', 'your', 'his', 'her', 'its', 'is', 'are',
            'our', 'their', 'whose', 'which', 'whichever', 'whatever', 'who', 'whom', 'whosever', 'whomever', 'whatever', 'somebody', 
            'someone', 'something', 'anybody', 'anyone', 'anything', 'nobody', 'none', 'no one', 'nothing', 'everybody', 'everyone', 'everything'
        ];
    } 

    /**
     * @return array
     */
    private function letterTranslate():array {
        return [
            "î" => "i",
            "ï" => "i",
            "é" => "e",
            "à" => "a",
            "è" => "e",
            "ô" => "o",
            "û" => "u",
            "ç" => "c",
            "ö" => "o",
            "ë" => "e",
            "â" => "a",
            "ê" => "e",
            "ù" => "u",
            "ü" => "u",
            "ä" => "a",
            "ß" => "ss",
            "ñ" => "n",
            "ø" => "o",
            "æ" => "ae",
            "œ" => "oe",
            "ł" => "l",
            "đ" => "d",
            "þ" => "th",
            "ð" => "th",
            "į" => "i",
            "ė" => "e",
            "ų" => "u",
            "ą" => "a",
            "ę" => "e",
            "ņ" => "n",
            "ķ" => "k",
            "ļ" => "l",
            "ž" => "z",
            "š" => "s",
            "ģ" => "g",
            "č" => "c",
            "ā" => "a",
            "ē" => "e",
            "ī" => "i",
            "ū" => "u",
            "ż" => "z",
            "ć" => "c",
            "ń" => "n",
            "ó" => "o",
            "ś" => "s",
            "ź" => "z",
            "ş" => "s",
            "ğ" => "g",
            "ı" => "i",
            "ő" => "o",
            "ű" => "u",
            "ŕ" => "r",
            "ĺ" => "l",
            "ť" => "t",
            "ý" => "y",
            "č" => "c",
            "ø" => "o",
            "å" => "a",
            "æ" => "ae",
            "œ" => "oe",
            "Î" => "I",
            "Ï" => "I",
            "É" => "E",
            "À" => "A",
            "È" => "E",
            "Ô" => "O",
            "Û" => "U",
            "Ç" => "C",
            "Ö" => "O",
            "Ë" => "E",
            "Â" => "A",
            "Ê" => "E",
            "Ù" => "U",
            "Ü" => "U",
            "Ä" => "A",
            "ẞ" => "SS",
            "Ñ" => "N",
            "Ø" => "O",
            "Æ" => "AE",
            "Œ" => "OE",
            "Ł" => "L",
            "Đ" => "D",
            "Þ" => "TH",
            "Ð" => "TH",
            "Į" => "I",
            "Ė" => "E",
            "Ų" => "U",
            "Ą" => "A",
            "Ę" => "E",
            "Ņ" => "N",
            "Ķ" => "K",
            "Ļ" => "L",
            "Ž" => "Z",
            "Š" => "S",
            "Ģ" => "G",
            "Č" => "C",
            "Ā" => "A",
            "Ē" => "E",
            "Ī" => "I",
            "Ū" => "U",
            "Ż" => "Z",
            "Ć" => "C",
            "Ń" => "N",
            "Ó" => "O",
            "Ś" => "S",
            "Ź" => "Z",
            "Ş" => "S",
            "Ğ" => "G",
            "İ" => "I",
            "Ő" => "O",
            "Ű" => "U",
            "Ŕ" => "R",
            "Ĺ" => "L",
            "Ť" => "T",
            "Ý" => "Y",
            "Č" => "C",
            "Ø" => "O",
            "Å" => "A",
            "Æ" => "AE",
            "Œ" => "OE"        
        ];
    }
    
}
