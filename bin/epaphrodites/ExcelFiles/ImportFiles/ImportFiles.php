<?php

namespace Epaphrodites\epaphrodites\ExcelFiles\ImportFiles;

class ImportFiles extends FilesExtension
{

    /**
     * @param mixed $ExcelFiles
     * @return array|bool
    */
    public function importExcelFiles( $ExcelFiles ):array|bool
    {

        if(isset($ExcelFiles) && in_array($_FILES['file']['type'], static::$FilesMimes)) 
        {
            
            $GetReader = $this->ExtenstionFiles($ExcelFiles);

            if($GetReader!==false){

                $SpreadSheet = $GetReader->load($_FILES['file']['tmp_name']);

                return $SpreadSheet->getActiveSheet()->toArray();

            }else{return false; }
            
        }else{return false; }
    }
}