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
            'answers' => ["I am a work assistance AI. I do not handle this kind of information.", "I am designed to assist with technical tasks, but this type of information is beyond my capabilities." , "I am an artificial intelligence specialized in technical assistance, however, this data does not fall within my processing capabilities."], 
            'type' => "txt",
        ];
    }

    /**
     * @return array
     */    
    public function defaultAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => ["I am a work assistance AI. I do not handle this kind of information.", "I am designed to assist with technical tasks, but this type of information is beyond my capabilities." , "I am an artificial intelligence specialized in technical assistance, however, this data does not fall within my processing capabilities."], 
            'type' => "txt",
        ];
    }    
}