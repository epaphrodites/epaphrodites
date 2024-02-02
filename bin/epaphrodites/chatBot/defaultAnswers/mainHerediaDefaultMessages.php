<?php

namespace Epaphrodites\epaphrodites\chatBot\defaultAnswers;

class mainHerediaDefaultMessages
{

    /**
     * @return array
     */
    public function defaultMessageInEnglishWhereNoAnswers():array{
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
    public function defaultMessageInFrenchWhereNoAnswers():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je suis une IA d'assistance professionnelle. Je ne gère pas ce type d'informations.",
                "Je suis un modèle de langage et je ne peux pas vous aider avec cette question.",
                "Je suis conçu pour aider avec des tâches techniques, mais pas pour ce type d'information.",
                "Je suis une intelligence artificielle spécialisée dans l'assistance technique, cependant, je ne traite pas ce genre d'information."
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
    public function defaultMessageInEnglishToGetMorePrecision():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "I can't understand your concern. Could you please be more explicit?." , 
                "Your concern isn't clear to me. Could you provide more details, please?",
                "To ensure accuracy, could you please provide more details?"
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
    public function defaultMessageInFrenchToGetMorePrecision():array{
        // Get bot default messages
        return [ 
            'answers' => [
                "Je ne comprends pas votre préoccupation. Pourriez-vous être plus explicite, s'il vous plaît ?",
                "Votre préoccupation n'est pas claire pour moi. Pourriez-vous fournir plus de détails, s'il vous plaît ?",
                "Pour garantir la précision, pourriez-vous fournir plus de détails, s'il vous plaît ?"
            ], 
            "context" => [],
            "type" => "txt",
            "language" => "fr",
            "actions" => "none"
        ];
    }  
}