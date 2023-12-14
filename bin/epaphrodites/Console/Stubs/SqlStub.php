<?php

namespace Epaphrodites\epaphrodites\Console\Stubs;

class SqlStub{

/**
 * @return string
*/    
public static function insertNoSql($name){

    $stub = 
    '
    /**
     * @param string $value1
     * @param string $value2
     * @return bool
    */
    '."public function $name".'($value1 , $value2){
        
        $document =
            [
                "value1" => $value1,
                "value2" => $value2,
            ];
        
        $this->db(1)->selectCollection("collection")->insertOne($document);

        $actions = "Titre action recente";
        $this->setting->noSqlActionsRecente($actions);

        return true;

    }'; 
        
    return $stub;    

}

/**
 * @return string
 */
public static function updateNoSql($name){

    $stub = 
    '
      /**
         * @param string $value1
         * @param string $value2
         * @return bool
       */
    '."public function $name".'($value1 , $value2 , $Id){
        
        $filter = [ "_id" => $Id ];
    
        $update = [
            \'$set\'=> [ 
                "value1" => $value1,
                "value2" => $value2
                ]
        ];   

        $this->db(1)->selectCollection("collection")->updateMany($filter, $update);

        $actions = "Titre action recente";
        $this->setting->noSqlActionsRecente($actions);

        return true;
    }'; 
        
    return $stub;    
}

/**
 * @return string
 */
public static function deleteNoSql($name){

    $stub = 
    '/**
     * @param string $value1
     * @return bool
     */
    '."public function $name".'($value1){
        
        $sql = $this->table("table")
                    ->where("id")
                    ->DQuery();
    
        static::process()->delete($sql, [$value1] , true ); 
        
        $actions = "Titre action recente";
        $this->setting->ActionsRecente($actions);      

        return true;
    }'; 
        
    return $stub;    
}


/**
 * @return string
 */
public static function selectNoSql($name){

$stub = 
'
  /**
    * @param string $value1
    * @return bool
   */
'."public function $name".'($value1){
        
        $sql = $this->table("table")
                    ->where("id")
                    ->SQuery();
    
        $Result = static::process()->select($sql, [$value1] , true );    
        
        return $Result;
    }'; 
        
    return $stub;    
}

public static function countNoSql($name){

$stub = 
'
  /**
    * @return int
   */
'." public function $name".'(){
        
        $result = $this->db(1)
            ->selectCollection("collection")
            ->countDocuments([]);

        return $result;
    }'; 
        
return $stub;    

}

/**
 * @return string
*/    
public static function insertSql($name){

    $stub = 
    '
    /**
     * @param string $value1
     * @param string $value2
     * @param string $value3
     * @return bool
    */
    '."public function $name".'($value1 , $value2 , $value3){
        
        $sql = $this->table("table")
                    ->insert(" value1 , value2 , value3 ")
                    ->values(" ? , ? , ? ")
                    ->IQuery();
    
    static::process()->insert($sql, [$value1 , $value2 , $value3] , true );                
    
    $actions = "Titre action recente";
    $this->setting->ActionsRecente($actions);

    return true;

    }'; 
        
    return $stub;    

}

/**
 * @return string
 */
public static function updateSql($name){

    $stub = 
    '
      /**
         * @param string $value1
         * @param string $value2
         * @return bool
       */
    '."public function $name".'($value1 , $value2 , $Id){
        
        $sql = $this->table("table")
                    ->set(["value1" , "value2"])
                    ->where("id")
                    ->UQuery();
    
        static::process()->update($sql, [$value1 , $value2 ,  $Id] , true );   
        
        $actions = "Titre action recente";
        $this->setting->ActionsRecente($actions);

        return true;
    }'; 
        
    return $stub;    
}

/**
 * @return string
 */
public static function deleteSql($name){

    $stub = 
    '/**
     * @param string $value1
     * @return bool
     */
    '."public function $name".'($value1){
        
        $sql = $this->table("table")
                    ->where("id")
                    ->DQuery();
    
        static::process()->delete($sql, [$value1] , true ); 
        
        $actions = "Titre action recente";
        $this->setting->ActionsRecente($actions);      

        return true;
    }'; 
        
    return $stub;    
}


/**
 * @return string
 */
public static function selectSql($name){

$stub = 
'
  /**
    * @param string $value1
    * @return bool
   */
'."public function $name".'($value1){
        
        $sql = $this->table("table")
                    ->where("id")
                    ->SQuery();
    
        $Result = static::process()->select($sql, [$value1] , true );    
        
        return $Result;
    }'; 
        
    return $stub;    
}

public static function countSql($name){

$stub = 
    '
    /**
        * @return int
    */
    '."public function $name".'(){
        
        $sql = $this->table("table")
                    ->SQuery("count(id) as nbre");
    
        $result = static::process()->select($sql, NULL , false );   
                
        return $result[0]["nbre"];
    }'; 
        
return $stub;    

}

protected static function SwicthRequestContent($type,$name){

    switch ($type) {

        case 'insert':
                return self::insertSql($name);
                break;

            case 'update':
                return self::updateSql($name);
                break;

            case 'delete':
                return self::deleteSql($name);
                break; 
            
            case 'count':
                return self::countSql($name);
                break;                   
            
            default:
                return self::selectSql($name);
                break;
        }
    }

    protected static function SwicthNoSqlRequestContent($type,$name){

        switch ($type) {
    
            case 'insert':
                    return self::insertNoSql($name);
                    break;
    
                case 'update':
                    return self::updateNoSql($name);
                    break;
    
                case 'delete':
                    return self::deleteNoSql($name);
                    break; 
                
                case 'count':
                    return self::countNoSql($name);
                    break;                   
                
                default:
                    return self::selectNoSql($name);
                    break;
            }
        }    


}