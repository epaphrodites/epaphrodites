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
            'answers' => ["I am a work assistance AI. I do not handle this kind of information."], 
            'type' => "txt",
        ];
    }

    /**
     * @return array
     */    
    public function defaultAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => ["I am a work assistance AI. I do not handle this kind of information."] , 
            'type' => "txt",
        ];
    }    
}