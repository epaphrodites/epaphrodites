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
     * @param bool|false $closeConnection Flag to indicate if connection should be closed after execution
     * @param int|1 $bd The database reference
     * @return array|null The fetched data
     */
    public function select(
        string $sqlChaine,
        array $datas = [],
        bool $param = false,
        bool $closeConnection = false,
        int $db = 1
    ): ?array{
        $connection = $this->dbConnect($db);
        $request = $connection->prepare($sqlChaine);

        if ($param) {
            foreach ($datas as $k => $v) {
                $request->bindValue(is_int($k) ? $k + 1 : $k, $v, PDO::PARAM_STR);
            }
        }

        $request->execute();

        $result = $request->fetchAll();

        if ($closeConnection) {
            $this->closeConnection($db);
        }

        return $result;
    }

    /**
     * SQL request execution
     * 
     * @param string $sqlChaine The SQL query
     * @param array|[] $datas The data for query parameters
     * @param bool|false $param Flag to indicate if query parameters are set
     * @param bool|false $closeConnection Flag to indicate if connection should be closed after execution
     * @param int|1 $bd The database reference
     * @return bool|null True if the execution is successful, otherwise false
     */
    public function runRequest(
        string $sqlChaine, 
        array $datas = [], 
        bool $param = false, 
        bool $closeConnection = false, 
        int $db = 1
    ): bool{
        $connection = $this->dbConnect($db);
        $connection->beginTransaction();

        try {
            $request = $connection->prepare($sqlChaine);

            if ($param) {
                foreach ($datas as $k => $v) {
                    $request->bindValue(is_int($k) ? $k + 1 : $k, $v, PDO::PARAM_STR);
                }
            }

            $result = $request->execute();

            if ($closeConnection) {
                $this->closeConnection($db);
            }

            $connection->commit();
            return $result;
            
        } catch (\Exception $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            return false;
        }
    }
}