<?php

namespace Epaphrodites\epaphrodites\define\config\traits;

use Epaphrodites\epaphrodites\ErrorsExceptions\epaphroditeException;



trait currentSubmit
{

    use currentFunctionNamespaces;

    private static array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];

    /**
     * Check if a variable exists in the $_POST array.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists in $_POST, false otherwise.
     */

    public static function isPost($key): bool
    {

        if (empty($key)) {
            throw new epaphroditeException('Invalid key');
        }

        return $_SERVER['REQUEST_METHOD'] === 'POST' && filter_input(INPUT_POST, $key, FILTER_DEFAULT) !== null;
    }

    /**
     * @param string $accepted
     * @return bool
     */
    public static function isValidMethod(bool $crsf = false, string $accepted = 'POST'): bool
    {

        $crsf === false ? :static::forcingTokenVerification();

        // Retrieve and sanitize the request method
        $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : null;
        
        // Check if the method is not null and is among the allowed methods
        return ($method !== null && in_array($method, self::$allowedMethods) && $method === $accepted);
    }

    /**
     * @param string $accepted
     * @return bool
     */
    public static function isValidApiMethod(bool $crsf = false, string $accepted = 'POST'): bool
    {

        $crsf === false ? :static::forcingApiTokenVerification();

        // Retrieve and sanitize the request method
        $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : null;
        
        // Check if the method is not null and is among the allowed methods
        return ($method !== null && in_array($method, self::$allowedMethods) && $method === $accepted);
    }    

    /**
     * @return void
     */
    private static function forcingTokenVerification():void {

        static::initNamespace()['crsf']->toForceCrsf() === false ? static::class('errors')->error_403() : NULL;
    }
    
    /**
     * @return array|null
     */
    private static function forcingApiTokenVerification():array|null {

        if (static::initNamespace()['crsf']->toForceCrsf() === false) {
            static::initNamespace()['response']->JsonResponse(400, ['error' => "Method not found"]);
            die;
        } else {
            return NULL;
        }
    }
     
    /**
     * Get the value from $_POST array for a given key with a default value.
     *
     * @param string $key The key to get.
     * @return mixed The value for the key in $_POST or an empty string if not set.
     */
    public static function getPost($key)
    {

        if (!isset($key) || $key === '') {
            throw new \InvalidArgumentException('Invalid key: Key is required and cannot be empty.');
        }
    
        if (empty($key)) {
            throw new epaphroditeException('Invalid key');
        }

        return static::noSpace($_POST[$key]) ?? '';
    }

   /**
     * Get the value from $_POST array for a given key with a default value.
     *
     * @param string $key The key to get.
     * @return mixed The value for the key in $_POST or an empty string if not set.
     */
    public static function isAjax($key)
    {

        if (!isset($key) || empty($key)) {
            throw new \InvalidArgumentException('Invalid key: Key is required and cannot be empty.');
        }
    
        try {
            $postData = $_SERVER['REQUEST_METHOD'] === 'POST' ? static::isPostJSON() : static::isGetJSON();

            if ($postData !== null) {
                $data = json_decode($postData, true);
                if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new \JsonException('JSON decoding error: ' . json_last_error_msg());
                }
                return static::noSpace($data[$key]) ?? null;
            }
        } catch (\JsonException $e) {
            throw $e;
        }
    
        return null;
    }    

    /**
     * @return null|string
     */
    private static function isPostJSON(): ?string
    {
        $parametres = array(); // Initializing an empty array to store POST parameters
    
        if ($_POST) {
            foreach ($_POST as $key => $value) {
                // Storing each POST parameter in the $parametres array
                $parametres[$key] = $value;
            }
    
            // Responding in JSON format
            return json_encode($parametres);
        }
    
        return null;
    }

   /**
     * @return null|string
     */
    private static function isGetJSON(): ?string
    {
        $parametres = array(); // Initializing an empty array to store GET parameters
    
        if ($_GET) {
            foreach ($_GET as $key => $value) {
                // Storing each GET parameter in the $parametres array
                $parametres[$key] = $value;
            }
    
            // Responding in JSON format
            return json_encode($parametres);
        }
    
        return null;
    }    
    
    /**
     * Check if a variable exists in the $_GET array.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists in $_GET, false otherwise.
     */
    public static function isGet($key): bool
    {

        if (empty($key)) {
            throw new epaphroditeException('Invalid key');
        }

        $value = filter_input(INPUT_GET, $key, FILTER_DEFAULT);

        if ($value === null || $value === false) {
            return false;
        }

        static::noSpace($value);

        return true;
    }

    /**
     * Get the value from $_GET array for a given key with a default value.
     *
     * @param string $key The key to get.
     * @return mixed The value for the key in $_GET or an empty string if not set.
     */
    public static function getGet($key)
    {

        if (empty($key)) {
            throw new epaphroditeException('Invalid key');
        }

        return static::noSpace($_GET[$key]) ?? '';
    }

    /**
     * Process data from a specified method and key, converting elements to integers if they exist.
     *
     * @param string $method The method to retrieve data from (default is 'POST').
     * @param string $key    The key of the data to be processed.
     *
     * @return array Processed array with integer elements or an empty array.
     */
    public static function isArray(string $key, string $method = 'POST'): array
    {

        if (empty($key)) {
            throw new epaphroditeException('Invalid key');
        }

        // Retrieve data based on the specified method and key
        $data = match (strtoupper($method)) {
            'GET' => $_GET[$key] ?? null,
            'POST' => $_POST[$key] ?? null,
            default => null,
        };

        // Check if data is an array and is not empty
        if (is_array($data) && !empty($data)) {
            return $data; // Return the array if it is valid
        }

        // Return the entire data retrieved or an empty array if it doesn't exist
        return is_array($data) ? $data : [];
    }

    /**
     * Checks if the value associated with a specified key in $_POST or $_GET (based on the method) is equal to a given index.
     *
     * @param string $key     The key to check in $_POST or $_GET.
     * @param string|int|null $index    The index or value to compare against.
     * @param string $method  The method to use, either 'POST' or 'GET' (default is 'POST').
     *
     * @return bool Returns true if the value associated with the key matches the given index, otherwise false.
     */
    public static function isSelected(string $key, string|int|null $index, string $method = 'POST'): bool
    {

        if (empty($key) || empty($index)) {
            throw new epaphroditeException('Invalid key');
        }

        $value = filter_input($method === 'GET' ? INPUT_GET : INPUT_POST, $key, FILTER_DEFAULT);

        return $value != null && $value != false && $value == $index;
    }

    /**
     * Checks if specific keys in a given method's data (GET or POST) are not empty.
     *
     * @param string $method The method to check (either 'GET' or 'POST').
     * @param array $keys An array containing keys to check in the data source.
     *
     * @return bool Returns true if all specified keys exist and are not empty in the data source, otherwise false.
     */
    public static function notEmpty(array $keys = [], string $method = 'POST'): bool
    {
        // Validate the HTTP method
        $method = strtoupper($method);
        if (!in_array($method, self::$allowedMethods, true)) {
            throw new \InvalidArgumentException('Invalid HTTP method provided.');
        }

        // Determine the data source based on the method
        $source = match ($method) {
            'GET' => $_GET,
            'POST' => self::filterInputArray(INPUT_POST),
            'PUT' => self::parseRawInputData(),
            default => [],
        };

        // Check if the data source is empty
        if (empty($source)) {
            return false;
        }

        // Check if each specified key exists in the data source and is not empty
        foreach ($keys as $key) {
            if (!array_key_exists($key, $source) || empty($source[$key])) {
                return false;
            }
        }

        return true;
    }

    private static function filterInputArray(int $type): array {
        $filtered = filter_input_array($type, [
            '*' => [
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags' => FILTER_REQUIRE_SCALAR,
                'options' => ['default' => '']
            ]
        ]);
    
        if ($filtered === null || $filtered === false) {
            throw new \RuntimeException('Failed to retrieve input data.');
        }
    
        return $filtered;
    }
    
    private static function parseRawInputData(): array {
        $rawData = file_get_contents('php://input');
        parse_str($rawData, $putData);
        return array_map('trim', $putData);
    }

    /**
     * Cleans up spaces in a string by trimming leading and trailing spaces,
     * and normalizing internal spaces by replacing multiple spaces with a single space.
     *
     * @param string $datas The input string to be cleaned.
     * @return string The cleaned string.
     */
    private static function noSpace($datas)
    {

        // Convert to string if it's not already a string
        $datas = is_string($datas) ? $datas : strval($datas);

        // Trim leading and trailing spaces
        $datas = is_string($datas) ? trim($datas) : '';

        // Normalize internal spaces (replace multiple spaces with a single space)
        $string = preg_replace('/\s+/', ' ', $datas);

        return $string;
    }
}
