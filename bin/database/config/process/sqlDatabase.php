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
    private function closeConnection(int $db, bool $state = false):void
    {
         $this->dbConnect($db, $state); // Placeholder function for disconnection
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

        try {
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
            $this->closeConnection($db, true);
        }

        return $result;

    } catch (\Throwable $e) {

        // Detailed error handling in non-production environments
        if (!_PRODUCTION_) {
            $errorType = get_class($e);
            $errorMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES);
            $errorFile = $e->getFile();
            $errorLine = $e->getLine();
            $sqlPreview = htmlspecialchars(substr($sqlChaine, 0, 200), ENT_QUOTES);
            $paramsPreview = htmlspecialchars(json_encode($datas, JSON_PRETTY_PRINT), ENT_QUOTES);

            error_log("[$errorType] $errorMessage in $errorFile on line $errorLine | SQL: $sqlPreview");

            echo <<<HTML
            <div style='background-color: #ffe6e6; border: 1px solid #ff4d4d; padding: 20px; margin: 20px;font-family: monospace;color: #990000;border-radius: 8px;box-shadow: 2px 2px 6px rgba(0,0,0,0.1);'>
                <strong>Database Exception:</strong> {$errorType}<br>
                <strong>Message:</strong> {$errorMessage}<br>
                <strong>File:</strong> {$errorFile}<br>
                <strong>Line:</strong> {$errorLine}<br><br>
                <strong>SQL Query:</strong><br>
                <div style='background: white; padding: 10px; margin: 5px 0; border-radius: 4px; color: #333;'>
                    {$sqlPreview}...
                </div>
                <strong>Parameters:</strong><br>
                <pre style='background: white; padding: 10px; margin: 5px 0; border-radius: 4px; color: #333;'>
                    {$paramsPreview}
                </pre>
            </div>
            HTML;
        } else {
            error_log("Database error: " . $e->getMessage());
        }

        return [];
    }       
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
    ):bool{
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
                $this->closeConnection($db, true);
            }

            $connection->commit();
            return $result;
            
        } catch (\Exception $e) {
             // Rollback transaction if an error occurs
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            // Error handling in non-production environments
            if (!_PRODUCTION_) {
                $errorType = get_class($e);
                $errorMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES);
                $errorFile = $e->getFile();
                $errorLine = $e->getLine();

                // Output styled error message with details
                echo <<<HTML
                <div style='background-color: #ffe6e6; border: 1px solid #ff4d4d; padding: 20px; margin: 20px;font-family: monospace;color: #990000;border-radius: 8px;box-shadow: 2px 2px 6px rgba(0,0,0,0.1);'>
                    <strong>Database Exception:</strong> {$errorType}<br>
                    <strong>Message:</strong> {$errorMessage}<br>
                    <strong>File:</strong> {$errorFile}<br>
                    <strong>Line:</strong> {$errorLine}<br><br>
                </div>
                HTML;
                
                exit;
            } else {
                error_log("Database error: " . $e->getMessage());
            }

            return false;
        }
    }
}