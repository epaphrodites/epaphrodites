<?php

namespace epaphrodite\epaphrodite\define\config\traits;

trait currentVariableNameSpaces
{

    /**
     * Configuration for different file formats and their corresponding classes
     * @var string[] $initExcelSetting
     * @return array
     */
    public static array $initExcelSetting = 
    [
        'Ods' => \PhpOffice\PhpSpreadsheet\Reader\Ods::class,
        'xls' => \PhpOffice\PhpSpreadsheet\Reader\Xls::class,
        'csv' => \PhpOffice\PhpSpreadsheet\Reader\Csv::class,
        'xlsx' => \PhpOffice\PhpSpreadsheet\Reader\Xlsx::class,
    ];

    /**
     * Configuration for different languages and their corresponding text message classes
     * @var string[] $initMessageCode
     * @return array
     */ 
    public static array $initMessageCode = 
    [
        'eng' => \Epaphrodite\epaphrodite\define\lang\eng\SetEnglishTextMessages::class,
        'french' => \Epaphrodite\epaphrodite\define\lang\fr\SetFrenchTextMessages::class,
    ];

    /**
     * @var string[] $initNamespace
     * @return array
    */     
    protected static array $initNamespace =
    [
        'env' => \Epaphrodite\epaphrodite\env\env::class,
        'paths' => \Epaphrodite\epaphrodite\path\paths::class,
        'errors' => \Epaphrodite\controllers\render\errors::class,
        'datas' => \Epaphrodite\database\datas\arrays\datas::class,
        'global' => \Epaphrodite\epaphrodite\auth\HardSession::class,
        'mail' => \Epaphrodite\epaphrodite\api\email\SendMail::class,
        'crsf' => \Epaphrodite\epaphrodite\CsrfToken\token_csrf::class,
        'session' => \Epaphrodite\epaphrodite\auth\session_auth::class,
        'pdf' => \Epaphrodite\epaphrodite\share\makePdf\pdfStubs::class,
        'msg' => \Epaphrodite\epaphrodite\define\SetTextMessages::class,
        'secure' => \Epaphrodite\epaphrodite\CsrfToken\csrf_secure::class,
        'cookies' => \Epaphrodite\epaphrodite\auth\SetUsersCookies::class,
        'qrcode' => \Epaphrodite\epaphrodite\QRCodes\GenerateQRCode::class,
        'verify' => \Epaphrodite\epaphrodite\env\VerifyInputCharacteres::class,
        'layout' => \Epaphrodite\epaphrodite\EpaphMozart\Templates\LayoutsConfig::class,
        'mozart' => \Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\SwitchersList::class,
    ];  

    /**
     * @var string[] $initDatabaseConfig
     * @return array
    */     
    protected static array $initDatabaseConfig =
    [
        'builders' => \Epaphrodite\database\query\Builders::class,
        'process' => \Epaphrodite\database\config\process\process::class,
        'seeder' => \Epaphrodite\database\config\process\checkDatabase::class,
    ];

    /**
     * @var string[] $initGuardsConfig
     * @return array
    */     
    public static array $initGuardsConfig =
    [
        'auth' => \Epaphrodite\epaphrodite\danho\DanhoAuth::class,
        'guard' => \Epaphrodite\epaphrodite\danho\GuardPassword::class,
        'session' => \Epaphrodite\epaphrodite\env\config\GeneralConfig::class,
        'sql' => \Epaphrodite\database\requests\mainRequest\select\auth::class,
    ];

    /**
     * @var string[] $initRightsConfig
     * @return array
    */     
    public static array $initRightsConfig =
    [
        'update' => \Epaphrodite\epaphrodite\yedidiah\UpdateRights::class,
        'delete' => \Epaphrodite\epaphrodite\yedidiah\YedidiaDeleted::class,
    ];    

    /**
     * @var string[] $initQrCodesConfig
     * @return array
    */     
    public static array $initQrCodesConfig =
    [
        'qrcode' => \chillerlan\QRCode\QRCode::class,
        'qroptions' => \chillerlan\QRCode\QROptions::class,
    ]; 

    /**
     * @var string[] $initQueryConfig
     * @return array
    */     
    public static array $initQueryConfig =
    [
        'count' => \Epaphrodite\database\requests\mainRequest\select\count::class,
        'param' => \Epaphrodite\database\requests\mainRequest\select\param::class,
        'getid' => \Epaphrodite\database\requests\mainRequest\select\get_id::class,
        'delete' => \Epaphrodite\database\requests\mainRequest\delete\delete::class,
        'update' => \Epaphrodite\database\requests\mainRequest\update\update::class,
        'insert' => \Epaphrodite\database\requests\mainRequest\insert\insert::class,
        'select' => \Epaphrodite\database\requests\mainRequest\select\select::class,
        'general' => \Epaphrodite\database\requests\mainRequest\select\general::class,
    ];     
    
    /**
     * @var string[] $initAuthConfig
     * @return array
    */      
    public static array $initAuthConfig =
    [
        'setting' => \Epaphrodite\epaphrodite\auth\SetSessionSetting::class,
    ];

    /**
     * Configuration for Twig
     * @var string[] $initTwigConfig
     * @return array
     */    
    public static $initTwigConfig =
    [
        'extension' => \Epaphrodite\epaphrodite\Extension\EpaphroditeExtension::class,
    ]; 

    /**
     * Check if the retrieved value is an object; if not, return a stdClass instance
     * @param string $key
     * @param array $config
     * @return object
     */
    public function getObject(array $config, string $key): object {
        
        return is_object( new $config[$key] ?? null) ? new $config[$key] : new \stdClass();
    }      
}