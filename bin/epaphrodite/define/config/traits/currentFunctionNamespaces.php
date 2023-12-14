<?php

namespace Epaphrodite\epaphrodite\define\config\traits;

trait currentFunctionNamespaces
{

    /**
     * Initialize namespaces for different components
     * @return array
     */
    public static function initNamespace():array {

        return [
            'env' => new \Epaphrodite\epaphrodite\env\env,
            'paths' => new \Epaphrodite\epaphrodite\path\paths,
            'errors' => new \Epaphrodite\controllers\render\errors,
            'datas' => new \Epaphrodite\database\datas\arrays\datas,
            'global' => new \Epaphrodite\epaphrodite\auth\HardSession,
            'mail' => new \Epaphrodite\epaphrodite\api\email\SendMail,
            'crsf' => new \Epaphrodite\epaphrodite\CsrfToken\token_csrf,
            'session' => new \Epaphrodite\epaphrodite\auth\session_auth,
            'pdf' => new \Epaphrodite\epaphrodite\share\makePdf\pdfStubs,
            'msg' => new \Epaphrodite\epaphrodite\define\SetTextMessages,
            'secure' => new \Epaphrodite\epaphrodite\CsrfToken\csrf_secure,
            'cookies' => new \Epaphrodite\epaphrodite\auth\SetUsersCookies,
            'qrcode' => new \Epaphrodite\epaphrodite\QRCodes\GenerateQRCode,
            'verify' => new \Epaphrodite\epaphrodite\env\VerifyInputCharacteres,
            'layout' => new \Epaphrodite\epaphrodite\EpaphMozart\Templates\LayoutsConfig,
            'eng' => new \Epaphrodite\epaphrodite\define\lang\eng\SetEnglishTextMessages,
            'french' => new \Epaphrodite\epaphrodite\define\lang\fr\SetFrenchTextMessages,
            'spanish' => new \Epaphrodite\epaphrodite\define\lang\esp\SetSpanichTextMessages,
            'mozart' => new \Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\SwitchersList,
        ];
    }

    /**
     * Initialize configuration for various components
     * @return array
     */
    public static function initConfig():array {

        return [
            'qrcode' => new \chillerlan\QRCode\QRCode,
            'auth' => new \Epaphrodite\epaphrodite\danho\DanhoAuth,
            'qroptions' => new \chillerlan\QRCode\QROptions,         
            'csv' => new \PhpOffice\PhpSpreadsheet\Reader\Csv,            
            'Ods' => new \PhpOffice\PhpSpreadsheet\Reader\Ods,
            'xls' => new \PhpOffice\PhpSpreadsheet\Reader\Xls,
            'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx,
            'guard' => new \Epaphrodite\epaphrodite\danho\GuardPassword,
            'addright' => new \Epaphrodite\epaphrodite\yedidiah\AddRights,
            'process' => new \Epaphrodite\database\config\process\process,
            'crsf' => new \Epaphrodite\epaphrodite\CsrfToken\validate_token,
            'updright' => new \Epaphrodite\epaphrodite\yedidiah\UpdateRights,
            'setting' => new \Epaphrodite\epaphrodite\auth\SetSessionSetting,
            'session' => new \Epaphrodite\epaphrodite\env\config\GeneralConfig,            
            'seeder' => new \Epaphrodite\database\config\process\checkDatabase,
            'delright' => new \Epaphrodite\epaphrodite\yedidiah\YedidiaDeleted,
            'listright' => new \Epaphrodite\epaphrodite\yedidiah\YedidiaGetRights,
            'python' => new \Epaphrodite\epaphrodite\translate\PythonCodesTranslate,
            'extension' => new \Epaphrodite\epaphrodite\Extension\EpaphroditeExtension,
        ];
    }

    /**
     * Initialize query components
     * @return array
     */
    public static function initQuery():array {

        return [
            'auth' => new \Epaphrodite\database\requests\mainRequest\select\auth,
            'count' => new \Epaphrodite\database\requests\mainRequest\select\count,
            'param' => new \Epaphrodite\database\requests\mainRequest\select\param,
            'getid' => new \Epaphrodite\database\requests\mainRequest\select\get_id,
            'delete' => new \Epaphrodite\database\requests\mainRequest\delete\delete,
            'update' => new \Epaphrodite\database\requests\mainRequest\update\update,
            'insert' => new \Epaphrodite\database\requests\mainRequest\insert\insert,
            'select' => new \Epaphrodite\database\requests\mainRequest\select\select,
            'general' => new \Epaphrodite\database\requests\mainRequest\select\general,
            'setting' => new \Epaphrodite\database\requests\typeRequest\sqlRequest\insert\setting,
        ];
    }  
    
    /**
     * Check if the retrieved value is an object; if not, return a stdClass instance
     * @param string $key
     * @param array $config
     * @return object
     */
    public function getFunctionObject(array $config, string $key): object {
        
        return is_object( $config[$key] ?? null) ? $config[$key] : new \stdClass();
    }      
}