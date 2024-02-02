<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait dafaultAnswers
{

    ///////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////// DFAULT MAIN BOT MESSAGES /////////////////////////////////////////////    

    public function epaphroditesDefaultMessageWhereNoResult(string $lang, object $class)
    {

       return match ($lang) {

            'fr' => $class->defaultMessageInFrenchWhereNoAnswers(),

            default => $class->defaultMessageInEnglishWhereNoAnswers(),
        };
    }

    public function epaphroditesDefaultMessageToGetMorePrecision(string $lang, object $class)
    {

        return match ($lang) {

            'fr' => $class->defaultMessageInFrenchToGetMorePrecision(),

            default => $class->defaultMessageInEnglishToGetMorePrecision(),
        };
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////// DFAULT HEREDIA MESSAGES /////////////////////////////////////////////

    public function herediaDefaultMessageWhereNoResult(string $lang, object $class)
    {

        return match ($lang) {

            'fr' => $class->defaultMessageInFrenchWhereNoAnswers(),

            default => $class->defaultMessageInEnglishWhereNoAnswers(),
        };
    }

    public function herediaDefaultMessageToGetMorePrecision(string $lang, object $class)
    {

        return match ($lang) {

            'fr' => $class->defaultMessageInFrenchToGetMorePrecision(),

            default => $class->defaultMessageInEnglishToGetMorePrecision(),
        };
    }
}
