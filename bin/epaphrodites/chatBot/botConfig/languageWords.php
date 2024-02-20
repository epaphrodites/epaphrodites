<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait languageWords
{

   /**
     * French words
     * @return array
    */    
    private function frenchLangWord(): array
    {
        return [
            'le', 'la', 'les', 'des', 'une', 'un', 'a', 'ce', 'cette', 'ces', 'celui', 'celle', 'ceux', 'celles', 'sur', 'du', 'de', 'leur', 'leurs', 'lui', 'eux', 'elle', 'elles','sui',
            'on', 'moi', 'toi', 'tu', 'je','il', 'soi', 'nous', 'vous', 'se', 'me' ,'mon', 'te', 'tom','y', 'en','ta', 'ton', 'son', 'ici', 'que', 'quoi', 'dont', 'quand', 'comment', 'combien', 
            'lesquels', 'lesquelles', 'duquel', 'desquels', 'desquelles', 'apres', 'avant', 'avec', 'chez', 'contre', 'dans', 'depuis', 'derriere', 'devant', 'entre', 'hors', 
            'jusque', 'par', 'parmi', 'pendant', 'pour', 'sans', 'sauf', 'selon', 'sous', 'vers', 'via', 'et', 'ou', 'mais', 'car', 'donc', 'or', 'ni', 'malgre', 'grace', 'mechamment',
            'vis', 'pourquoi', 'qui', 'quel', 'quelle', 'quels', 'quelles', 'lequel', 'laquelle', 'communaute', 'plus', 'moins', 'aussi', 'autant', 'tres', 'bien', 'mal', 'peu', 'beaucoup',
            'trop', 'assez', 'vraiment', 'absolument', 'apparemment', 'bientot', 'bref', 'brusquement', 'certainement', 'constamment', 'abord', 'ailleurs', 'habitude', 'deja', 'demain', 'emblee',
            'densement', 'dernierement', 'egalement', 'encore', 'enfin', 'enfouie', 'ensemble', 'ensuite', 'entierement', 'entre-temps', 'environ', 'evidemment', 'exclusivement', 
            'facilement', 'finalement', 'fort', 'franchement', 'frequemment', 'froidement', 'furieusement', 'generalement', 'gentiment', 'hardiment', 'hativement', 'heureusement', 
            'illegalement', 'immediatement', 'imparfaitement', 'infiniment', 'ingenument', 'involontairement', 'jamais', 'longtemps', 'lourdement', 'malheureusement', 'malicieusement',
            'mediocrement', 'meme', 'mensuellement', 'miserablement', 'modestement', 'naturellement', 'neanmoins', 'necessairement', 'sommes', 'importe', 'import', 'notamment', 'nouvellement', 
            'nulle' , 'part', 'obstinement', 'occasionnellement','etre', 'somme', 'precieusement', 'preferablement', 'parfois', 'partout', 'peniblement', 'peut', 'pleinement', 'plutot', 'poliment', 
            'precedemment', 'probablement', 'promptement', 'proportionnellement', 'provisoirement', 'publiquement', 'quelquefois', 'quelque', 'rarement', 'nom', 'nomme', 'appel',
            'recemment', 'rapidement', 'regulierement', 'relativement', 'rigidement', 'sainement', 'serieusement', 'souvent', 'soigneusement', 'strictement', 'suffisamment', 'superficiellement', 
            'surtout', 'tantot', 'tard', 'tardivement', 'tellement', 'tendrement', 'tout', 'coup', 'suite', 'droit' , 'gauche', 'toutefois', 'tristement', 'vaguement', 'vite', 'vivement', 'init' ,
            'volontairement', 'vraisemblablement', 'presque', 'propos', 'sais' , 'information' , 'parl' , 'salut' , 'salutation' , 'bonjour', 'bonsoir', 'janvier', 'fevrier', 'mars' , 'avril' , 'mai' , 'juin' , 'juillet' , 'aout',
            'septembre', 'octobre' , 'novembre', 'decembre','jour','recente','aider','remonte','entrainement', 'install', 'installation'
        ];
    }
    

    /**
     * English words
     * @return array
    */
    private function englishLangWord(): array
    {
        return [
            'the', 'a', 'an', 'am', 'this', 'that', 'these', 'those', 'some', 'any', 'each', 'every', 'my', 'your', 'his', 'her', 'its', 'from', 'advanced',
            'our', 'their', 'whose', 'which', 'whichever', 'whatever', 'who', 'whom', 'whosever', 'whomever', 'somebody', 'out', 'one', 'make',
            'someone', 'something', 'anybody', 'anyone', 'anything', 'nobody', 'none', 'no', 'nothing', 'everybody', 'everyone', 'everything',
            'of', 'and', 'is', 'help', 'can', 'most', 'to', 'in', 'for', 'on', 'with', 'by', 'at', 'as', 'do', 'does', 'did', 'done', 'will', 'would',
            'should', 'could', 'may', 'might', 'must', 'shall', 'into', 'over', 'under', 'through', 'before', 'after', 'between', 'among', 'about',
            'against', 'above', 'below', 'around', 'beside', 'besides', 'off', 'onto', 'up', 'down', 'throughout', 'toward', 'towards', 'inside',
            'outside', 'beneath', 'beyond', 'within', 'without', 'once', 'twice', 'thrice', 'often', 'seldom', 'rarely', 'never', 'always',
            'sometimes', 'everywhere', 'nowhere', 'somewhere', 'anywhere', 'there', 'here', 'when', 'where', 'why', 'how', 'what', 'whose', 'since',
            'so', 'but', 'or', 'if', 'unless', 'while', 'until', 'whether', 'because', 'though', 'although', 'yet', 'still', 'even', 'just', 'only',
            'both', 'either', 'neither', 'also', 'too', 'indeed', 'nevertheless', 'furthermore', 'meanwhile', 'otherwise', 'therefore', 'thus',
            'hence', 'moreover', 'instead', 'accordingly', 'consequently', 'anyway', 'anyhow', 'somehow', 'well', 'great', 'good', 'better', 'best',
            'bad', 'worse', 'worst', 'far', 'farther', 'farthest', 'much', 'more', 'most', 'little', 'less', 'least', 'many', 'few', 'fewer',
            'fewest', 'other', 'another', 'others', 'anymore', 'somewhat', 'anytime', 'everytime', 'noone', 'nothing', 'anyway', 'someway', 'noway', 'child',
            'whoever', 'whatever', 'whenever', 'wherever', 'however', 'whyever', 'whichever', 'whosever', 'please', 'clear', 'clean', 'mother', 'father',
            'hello', 'morning' , 'hi', 'generate', 'create', 'build','generated', 'created', 'building', 'tell', 'update', 'evening', 'want', 'children',
            'database', 'databases', 'open', 'tool', 'developer', 'developers', 'feature', 'same', 'function', 'recent', 'thank', 'download', 'readme', 'instal',
            'available', 'workflows', 'start', 'started', 'though', 'insert', 'create', 'update', 'delete', 'power', 'custom' ,'awesome', 'work', 'do', 'give',
            'janvier', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'day', 'night', 'help'
        ];
    }
}