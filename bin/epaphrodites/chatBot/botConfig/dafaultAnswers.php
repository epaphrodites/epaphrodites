<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait dafaultAnswers
{

    /**
     * @return array
     */
    public function epaphroditesDefaultAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "I am a work assistance AI. I do not handle this kind of information.", 
                "I am a language model, and I am unable to assist you with this matter.",
                "I am designed to assist with technical tasks, but this type of information is beyond my capabilities." , 
                "I am an artificial intelligence specialized in technical assistance, however, this data does not fall within my processing capabilities."
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "eng",
            "actions" => "none"
        ];
    }

    /**
     * @return array
     */    
    public function defaultAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "I am a work assistance AI. I do not handle this kind of information.", 
                "I am a language model, and I am unable to assist you with this matter.",
                "I am designed to assist with technical tasks, but this type of information is beyond my capabilities." , 
                "I am an artificial intelligence specialized in technical assistance, however, this data does not fall within my processing capabilities.
                "], 
            "context" => [],
            "type" => "txt",
            "language" => "eng",
            "actions" => "none"
        ];
    }
    
    /**
     * @return array
     */    
    public function needMoreAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "I can't understand your concern. Could you please be more explicit?." , 
                "Your concern isn't clear to me. Could you provide more details, please?",
                "Your concern is unclear to me. Could you elaborate further, please?"
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "eng",
            "actions" => "none"
        ];
    } 
    
    /**
     * @return array
     */    
    public function mainNeedMoreAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "I can't understand your concern. Could you please be more explicit?." , 
                "Your concern isn't clear to me. Could you provide more details, please?",
                "Your concern is unclear to me. Could you elaborate further, please?"
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "eng",
            "actions" => "none"
        ];
    }     
}