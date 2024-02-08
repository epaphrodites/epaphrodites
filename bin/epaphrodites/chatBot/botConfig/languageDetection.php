<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait languageDetection
{

    /**
     * @param string $login
     * @param string $jsonFiles
     * @return string
     */
    private function detectLastLang(string $login , string $jsonFiles = 'BotSession'):string
    {
        $jsonDatas = $this->loadJsonFile('user'.$jsonFiles);

        for ($i = count($jsonDatas) - 1; $i >= 0; $i--) {
            $value = $jsonDatas[$i];
            if ($value['login'] === $login) {
                return $value['language'];
            }
        }

        return "eng";
    }

    /**
     * @param string $userMessages
     * @param string $login
     * @return string 
    */    
    private function detectMainLanguage(string $userMessages , string $login, string $jsonFiles = 'BotSession'):string
    {
        $languageDetected = "";

        $userMessages = $this->normalizeWords($userMessages);

        $detectLastLanguage = $this->detectLastLang($login , $jsonFiles);
        
        (int) $englishWord = count(array_intersect($this->englishLangWord(), $userMessages));
        
        (int) $frenchhWord = count(array_intersect($this->frenchLangWord(), $userMessages));

        if(empty($englishWord)&&empty($frenchhWord)){

            $languageDetected = $detectLastLanguage;
        }else{
            $languageDetected = $frenchhWord > $englishWord ? 'fr' : 'eng';
        }
        
        return $languageDetected;
    }

    /**
     * @return array
    */    
    private function frenchLangWord(): array
    {
        return [
            'le', 'la', 'les', 'des', 'une', 'un', 'l\'', 'à', 'ce', 'cette', 'ces', 'celui', 'celle', 'ceux', 'celles', 'sur',
            'du', 'de la', 'de l\'', 'de', 'leur', 'leurs', 'lui', 'eux', 'elle', 'elles', 'on', 'moi', 'toi', 'tu', 'je','il',
            'soi', 'nous', 'vous', 'se', 'me', 'te', 'y', 'en', 'qui', 'que', 'quoi', 'dont', 'où', 'quand', 'comment', 'combien',
            'à', 'après', 'avant', 'avec', 'chez', 'contre', 'dans', 'depuis', 'derrière', 'devant', 'entre', 'hors', 'jusque', 'par', 'parmi',
            'pendant', 'pour', 'sans', 'sauf', 'selon', 'sous', 'vers', 'via', 'et', 'ou', 'mais', 'car', 'donc', 'or', 'ni', 'malgré', 'grâce',
            'vis-à-vis', 'pourquoi', 'qui', 'que', 'quoi', 'quel', 'quelle', 'quels', 'quelles', 'combien', 'où', 'quand', 'lequel', 'laquelle',
            'lesquels', 'lesquelles', 'dont', 'duquel', 'de laquelle', 'desquels', 'desquelles', 'à quel', 'à quelle', 'à quels', 'à quelles', 'de quel',
            'de quelle', 'de quels', 'de quelles', 'pour quel', 'pour quelle', 'pour quels', 'pour quelles', 'plus', 'moins', 'aussi', 'autant',
            'tant', 'très', 'bien', 'mal', 'peu', 'beaucoup', 'trop', 'assez', 'vraiment', 'absolument', 'apparemment', 'bientôt','bientot', 'bref',
            'brusquement', 'certainement', 'constamment', 'd\'abord', 'd\'ailleurs', 'd\'après', 'd\'habitude', 'déjà', 'demain', 'd\'emblée','d\'emblee',
            'densement', 'dernièrement', 'ici', 'donc', 'également', 'encore', 'enfin', 'enfouie', 'ensemble', 'ensuite', 'entièrement',
            'entre-temps', 'environ', 'évidemment', 'exclusivement', 'facilement', 'finalement', 'fort', 'franchement', 'fréquemment', 'froidement',
            'furieusement', 'généralement', 'gentiment', 'hardiment', 'hâtivement', 'heureusement', 'illégalement', 'immédiatement', 'imparfaitement',
            'infiniment', 'ingénument', 'involontairement', 'jamais', 'longtemps', 'lourdement', 'malheureusement', 'malicieusement', 'méchamment',
            'médiocrement', 'même', 'mensuellement', 'misérablement', 'modestement', 'naturellement', 'néanmoins', 'nécessairement', 'n\'importe où',
            'n\'importe quand', 'n\'importe comment', 'n\'importe qui', 'notamment', 'nouvellement', 'nulle part', 'obstinément', 'occasionnellement',
            'ou', 'parfois', 'partout', 'péniblement', 'peut-être', 'pleinement', 'plutôt', 'poliment', 'précédemment', 'précieusement', 'préférablement',
            'probablement', 'promptement', 'proportionnellement', 'provisoirement', 'publiquement', 'quelquefois', 'quelque part', 'rarement',
            'récemment', 'rapidement', 'régulièrement', 'relativement', 'rigidement', 'sainement', 'sérieusement', 'souvent', 'soigneusement',
            'strictement', 'suffisamment', 'superficiellement', 'surtout', 'tant', 'tantôt', 'tard', 'tardivement', 'tellement', 'tendrement','efface',
            'tout', 'tout à coup', 'tout de suite', 'tout droit', 'toutefois', 'tristement', 'trop tard', 'vaguement', 'vite', 'vivement', 'init' ,
            'volontairement', 'vraiment', 'vraisemblablement', 'presque', 'propos', 'sais' , 'information' , 'parle' , 'salut' , 'bonjour' , 'bonsoir',
            'janvier', 'fevrier', 'ta','jour','recente','suis','aider','remonte','entrainement'
        ];
    }
    
    /**
     * @return array
    */
    private function englishLangWord(): array
    {
        return [
            'the', 'a', 'an', 'this', 'that', 'these', 'those', 'some', 'any', 'each', 'every', 'my', 'your', 'his', 'her', 'its', 'from',
            'our', 'their', 'whose', 'which', 'whichever', 'whatever', 'who', 'whom', 'whosever', 'whomever', 'somebody', 'out',
            'someone', 'something', 'anybody', 'anyone', 'anything', 'nobody', 'none', 'no one', 'nothing', 'everybody', 'everyone', 'everything',
            'of', 'and', 'is', 'help', 'can', 'most', 'to', 'in', 'for', 'on', 'with', 'by', 'at', 'as', 'do', 'does', 'did', 'done', 'will', 'would',
            'should', 'could', 'may', 'might', 'must', 'shall', 'into', 'over', 'under', 'through', 'before', 'after', 'between', 'among', 'about',
            'against', 'above', 'below', 'around', 'beside', 'besides', 'off', 'onto', 'up', 'down', 'throughout', 'toward', 'towards', 'inside',
            'outside', 'beneath', 'beyond', 'within', 'without', 'once', 'twice', 'thrice', 'often', 'seldom', 'rarely', 'never', 'always',
            'sometimes', 'everywhere', 'nowhere', 'somewhere', 'anywhere', 'there', 'here', 'when', 'where', 'why', 'how', 'what', 'whose', 'since',
            'so', 'but', 'or', 'if', 'unless', 'while', 'until', 'whether', 'because', 'though', 'although', 'yet', 'still', 'even', 'just', 'only',
            'both', 'either', 'neither', 'also', 'too', 'indeed', 'nevertheless', 'furthermore', 'meanwhile', 'otherwise', 'therefore', 'thus',
            'hence', 'moreover', 'instead', 'accordingly', 'consequently', 'anyway', 'anyhow', 'somehow', 'well', 'great', 'good', 'better', 'best',
            'bad', 'worse', 'worst', 'far', 'farther', 'farthest', 'much', 'more', 'most', 'little', 'less', 'least', 'many', 'few', 'fewer',
            'fewest', 'other', 'another', 'others', 'anymore', 'somewhat', 'anytime', 'everytime', 'noone', 'nothing', 'anyway', 'someway', 'noway',
            'no one', 'whoever', 'whatever', 'whenever', 'wherever', 'however', 'whyever', 'whichever', 'whosever', 'please', 'clear', 'clean',
            'hello', 'morning' , 'hi', 'generate', 'create', 'build','generated', 'created', 'building', 'tell', 'update', 'evening'
        ];
    }
}