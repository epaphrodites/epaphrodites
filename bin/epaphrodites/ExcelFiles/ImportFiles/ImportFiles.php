<?php

namespace Epaphrodites\epaphrodites\ExcelFiles\ImportFiles;

class ImportFiles extends FilesExtension
{

    /**
     * @param mixed $ExcelFiles
     * @return array|bool
    */
    public function importExcelFiles( $ExcelFiles, $key = '__file__' ):array|bool
    {

        if(isset($ExcelFiles) && in_array($_FILES[$key]['type'], static::$FilesMimes)) 
        {

            $GetReader = $this->ExtenstionFiles($ExcelFiles);

            if($GetReader!==false){

                $SpreadSheet = $GetReader->load($_FILES[$key]['tmp_name']);

                return $SpreadSheet->getActiveSheet()->toArray();

            }else{return false; }
            
        }else{return false; }
    }
}