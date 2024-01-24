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
    public function select(string $sqlChaine, array $datas = [], ?bool $param = false, ?bool $etat = false, ?int $bd = 1): array|NULL
    {
        $request = $this->dbConnect($bd)->prepare($sqlChaine);

        if ($param === true) {
            foreach ($datas as $k => &$v) {
                $request->bindParam($k + 1, $datas[$k], PDO::PARAM_STR);
            }
        }

        // Close the connection if $etat is true (or not null)
        $etat === false ?: $this->closeConnection($bd);

        $request->execute();

        return $request->fetchAll(); // Fetch all selected data
    }

    /**
     * SQL request execution
     * 
     * @param string|null $sqlChaine The SQL query
     * @param array|null $datas The data for query parameters
     * @param bool|null $param Flag to indicate if query parameters are set
     * @param bool|null $etat Flag to indicate if connection should be closed after execution
     * @param int|1 $bd The database reference
     * @return bool|null True if the execution is successful, otherwise false
     */
    public function runRequest(string $sqlChaine, array $datas = [], ?bool $param = false, ?bool $etat = false, ?int $bd = 1): bool|NULL
    {
        $request = $this->dbConnect($bd)->prepare($sqlChaine);

        if ($param === true) {
            foreach ($datas as $k => &$v) {
                $request->bindParam($k + 1, $datas[$k], PDO::PARAM_STR);
            }
        }

        // Close the connection if $etat is true (or not null)
        $etat === false ?: $this->closeConnection($bd);

        return $request->execute(); // Execute the SQL query and return execution status
    }
}
