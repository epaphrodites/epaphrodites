<?php

namespace Epaphrodites\epaphrodites\env\pyEnv;

trait pyEnv{

    /**
     * To use this function, you must install python 3
     * and run this command "pip install pycryptodome"
     * @param null|string $value
     * @param null|string $type
     * @return mixed
     */
    public function pyEncryptDecrypt(
        ?string $value, 
        ?string $type
    ):mixed
    {

        return static::initConfig()['python']->executePython($type, ['value' => $value]);
    }

    /**
     * To use this function, you must install python 3
     * and run this command "pip install pytesseract"
     * E.g : $imgPath = $_FILES['file']['tmp_name']
     * @param mixed|null $imgPath
     * @return mixed
     */
    public function pyConvertImgToText($imgPath)
    {

        if (!file_exists($imgPath)) {
            throw new \Exception("Image paths are not valid.");
        }

        return static::initConfig()['python']->executePython('convert_img_to_text', ["img" => $imgPath]);
    }

    /**
     * To use this function, you must install python 3
     * and run this commande "pip install PyPDF2"
     * E.g : $imgPath = $_FILES['file']['tmp_name']
     * @param mixed|null $pdfPath
     * @return mixed
     */
    public function pyConvertPdfToText($pdfPath)
    {

        if (!file_exists($pdfPath)) {
            throw new \Exception("Document paths are not valid.");
        }

        return static::initConfig()['python']->executePython('convert_pdf_to_text', ["pdf" => $pdfPath]);
    }    
}