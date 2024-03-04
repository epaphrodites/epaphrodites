<?php

declare(strict_types=1);

namespace Epaphrodites\database\config\process;

use PDO;
use Database\Epaphrodites\config\SwitchDatabase;
use Epaphrodites\database\config\Contracts\DatabaseRequest;

class sqlDatabase extends SwitchDatabase implements DatabaseRequest
{

    /**
     * Disconnection from the database
     * 
     * @param int $bd
     * @return int|null
     */
    private function closeConnection(int $bd): int|null
    {
        return NULL; // Placeholder function for disconnection
    }

    /**
     * SQL request to select data
     * 
     * @param string|null $sqlChaine The SQL query
     * @param array|null $datas The data for query parameters
     * @param bool|null $param Flag to indicate if query parameters are set
     * @param bool|null $etat Flag to indicate if connection should be closed after execution
     * @param int|1 $bd The database reference
     * @return array|null The fetched data
     */
    public function select(string $sqlChaine, array $datas = [], bool $param = false, bool $etat = false, int $bd = 1): ?array
    {
        $connection = $this->dbConnect($bd);
        $request = $connection->prepare($sqlChaine);
    
        if ($param) {
            foreach ($datas as $key => &$value) {
                $request->bindParam($key + 1, $value, PDO::PARAM_STR);
            }
        }
    
        $request->execute();
    
        $result = $request->fetchAll();
        
        // Close the connection if $etat is true (or not null)
        if ($etat) {
            $this->closeConnection($bd);
        }
    
        return $result;
    }
    

    /**
     * SQL request execution
     * 
     * @param string $sqlChaine The SQL query
     * @param array|[] $datas The data for query parameters
     * @param bool|false $param Flag to indicate if query parameters are set
     * @param bool|false $etat Flag to indicate if connection should be closed after execution
     * @param int|1 $bd The database reference
     * @return bool|null True if the execution is successful, otherwise false
     */
    public function runRequest(string $sqlChaine, array $datas = [], bool $param = false, bool $etat = false, int $bd = 1): ?bool
    {
         $connection = $this->dbConnect($bd);

         $connection->beginTransaction();
        
        try {
            $request =  $connection->prepare($sqlChaine);
    
            if ($param) {
                foreach ($datas as $k => &$v) {
                    $request->bindParam($k + 1, $datas[$k], PDO::PARAM_STR);
                }
            }
    
            $result = $request->execute();
            $etat === false ?: $this->closeConnection($bd);
             $connection->commit();
    
            return $result;
            
        } catch (\Exception $e) {
             $connection->rollBack();
            
            return false;
        }
    }
    
}
