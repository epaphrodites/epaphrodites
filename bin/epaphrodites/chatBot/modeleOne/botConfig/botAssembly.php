<?php

namespace Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig;


trait botAssembly
{

    /**
     * @param array $userQuestions,
     * @param string $context,
     * @param array $assembly,
     * @param string $key
     * @param array $name
     * @return array
     */
    private function assemblyWords( array $userQuestions, array $assembly , array $name , string $key , string $context , array $botAnswers, array $alias):string{

        $wordToRemove = $this->aliasAndKey($key , $alias);

        $userQuestions = $this->removeSimilarWords( array_unique($userQuestions), $wordToRemove);

        $getName = $this->getMainResultName( $userQuestions , $name );

        $answers = match (true) {
            ($context === 'makeControleur' || $context === 'greeting' || $context === 'salutation') && !empty($getName) => str_replace('{name}', $getName, $this->answersChanging($assembly)),
            default => $this->answersChanging($botAnswers),
        };
        
        return $answers;
    }

    /**
     * @param string $key
     * @param array $alias
     * @return array
     */
    private function aliasAndKey(string $key , array $alias):array{

        $key = preg_replace('/[\[\]\{\}\(\)]/', '', $key);
        $key = str_replace(',', ' ', $key);

        return [...explode(" ", $key), ...$alias];
    }

    /**
     * @param array $userQuestions
     * @param array $wordToRemove
     * @param float $threshold
     * @return array
     */
    private function removeSimilarWords(array $userQuestions, array $wordsToRemove, float $threshold = 0.8):array {

        foreach ($userQuestions as $key => $word) {

            foreach ($wordsToRemove as $wordToRemove) {

                similar_text(mb_strtolower($word), mb_strtolower($wordToRemove), $similarity);
                if ($similarity >= $threshold * 100) {
                    unset($userQuestions[$key]);
                    break;
                }
            }
        }

        return array_values($userQuestions);
    }
}