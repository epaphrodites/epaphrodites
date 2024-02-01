<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait dafaultAnswers
{

    /**
     * @return array
     */
    public function epaphroditesDefaultEnglishAnswers():array{
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
    public function epaphroditesDefaultFrenchAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je suis une IA d'assistance professionnelle. Je ne gère pas ce type d'informations.",
                "Je suis un modèle de langage et je ne peux pas vous aider avec cette question.",
                "Je suis conçu pour aider avec des tâches techniques, mais ce type d'information dépasse mes capacités.",
                "Je suis une intelligence artificielle spécialisée dans l'assistance technique, cependant, ces données dépassent mes capacités de traitement."
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "fr",
            "actions" => "none"
        ];
    }   
    
/**
     * @return array
     */    
    public function needMoreAnswersInEnglish():array{
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
    public function needMoreAnswersInFrench():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je ne comprends pas votre préoccupation. Pourriez-vous être plus explicite, s'il vous plaît ?",
                "Votre préoccupation n'est pas claire pour moi. Pourriez-vous fournir plus de détails, s'il vous plaît ?",
                "Votre préoccupation n'est pas claire pour moi. Pourriez-vous élaborer davantage, s'il vous plaît ?"
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "fr",
            "actions" => "none"
        ];
    }      

    /**
     * @return array
     */    
    public function defaultEnglishAnswers():array{
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
    public function defaultFrenchAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je suis une IA d'assistance professionnelle. Je ne gère pas ce type d'informations.",
                "Je suis un modèle de langage et je ne peux pas vous aider avec cette question.",
                "Je suis conçu pour aider avec des tâches techniques, mais ce type d'information dépasse mes capacités.",
                "Je suis une intelligence artificielle spécialisée dans l'assistance technique, cependant, ces données dépassent mes capacités de traitement."
                ], 
            "context" => [],
            "type" => "txt",
            "language" => "fr",
            "actions" => "none"
        ];
    }      
    
    /**
     * @return array
     */    
    public function mainNeedFrenchMoreAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je ne peux pas comprendre votre préoccupation. Pourriez-vous être plus explicite, s'il vous plaît ?" , 
                "Votre préoccupation n'est pas claire pour moi. Pourriez-vous fournir plus de détails, s'il vous plaît ?",
                "Votre préoccupation n'est pas claire pour moi. Pourriez-vous élaborer davantage, s'il vous plaît ?"
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "fr",
            "actions" => "none"
        ];
    }  
    
    /**
     * @return array
     */    
    public function mainNeedEnglishMoreAnswers():array{
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