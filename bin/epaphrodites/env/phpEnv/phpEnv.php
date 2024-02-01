<?php

namespace Epaphrodites\epaphrodites\env\phpEnv;

trait phpEnv{

    private string $chaineTranslate;

    /**
     * Truncate a string to a specified length with optional separator and tail.
     *
     * @param string|null $string The input string to be truncated.
     * @param int $limit The maximum length of the truncated string.
     * @param string $separator The separator to add after the truncated content.
     * @param string $tail The tail to append after the separator.
     * @return string The truncated and formatted string.
     */
    public function truncate(?string $string = null, int $limit = 100, string $separator = '...', string $tail = '')
    {
        if (strlen($string) > $limit) {
            // Truncate the string to the specified limit
            $string = rtrim(mb_strimwidth($string, 0, $limit, '', 'UTF-8')) . $separator . $tail;
        }

        // Return the truncated and formatted string
        return $this->chaine($string);
    }

    /** 
     * @param mixed $date
     **/
    public function date_chaine($date)
    {
        $formatter = new \IntlDateFormatter('fr_FR.utf8', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $timestamp = strtotime($date);

        return $formatter->format($timestamp);
    }

    /**
     * @param mixed $date
     * @return void
     */
    public function LongDate($date)
    {

        $dateTime = new \DateTime($date);

        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::MEDIUM);

        $dateLong = $formatter->format($dateTime);

        echo $dateLong;
    }

    /**
     * Transform to ISO code
     * @param string|null $chaine
     * 
     * @return mixed
     */
    public function chaine(?string $chaine = null)
    {
        if (empty($chaine)) {
            return null;
        }

        return match (true) {
            (bool)preg_match('/&#039;/', $chaine) => str_replace('&#039;', "'", $chaine),
            (bool)preg_match('/&#224;/', $chaine) => str_replace('&#224;', 'à', $chaine),
            (bool)preg_match('/&#225;/', $chaine) => str_replace('&#225;', 'á', $chaine),
            (bool)preg_match('/&#226;/', $chaine) => str_replace('&#226;', 'â', $chaine),
            (bool)preg_match('/&#227;/', $chaine) => str_replace('&#227;', 'ã', $chaine),
            (bool)preg_match('/&#228;/', $chaine) => str_replace('&#228;', 'ä', $chaine),
            (bool)preg_match('/&#230;/', $chaine) => str_replace('&#230;', 'æ', $chaine),
            (bool)preg_match('/&#231;/', $chaine) => str_replace('&#231;', 'ç', $chaine),
            (bool)preg_match('/&#232;/', $chaine) => str_replace('&#232;', 'è', $chaine),
            (bool)preg_match('/&#233;/', $chaine) => str_replace('&#233;', 'é', $chaine),
            (bool)preg_match('/&#234;/', $chaine) => str_replace('&#234;', 'ê', $chaine),
            (bool)preg_match('/&#235;/', $chaine) => str_replace('&#235;', 'ë', $chaine),
            (bool)preg_match('/&#238;/', $chaine) => str_replace('&#238;', 'î', $chaine),
            (bool)preg_match('/&#239;/', $chaine) => str_replace('&#239;', 'ï', $chaine),
            (bool)preg_match('/&#244;/', $chaine) => str_replace('&#244;', 'ô', $chaine),
            (bool)preg_match('/&#251;/', $chaine) => str_replace('&#251;', 'û', $chaine),
            (bool)preg_match('/&amp;/', $chaine) => str_replace('&amp;', '&', $chaine),
            default => $chaine,
        };
    }

    /**
     * For transcoding values in an Excel generated (french)
     *
     * @param string $chaine
     * @return string
     */
    public function translate_fr(string $chaine)
    {

        $this->chaineTranslate = iconv('Windows-1252', 'UTF-8//TRANSLIT', $chaine);

        return $this->chaineTranslate;
    }

    /**
     * @param array|[] $target
     * @param array|[] $files
     * @return bool
     */
    public function UplaodFiles(?array $target = [], ?array $files = []): bool
    {
        foreach ($files as $key => $value) {

            move_uploaded_file($this->GetFiles($key), $target[$key] . '/' . $value);
        }

        return true;
    }

    /**
     * Clean directory
     *
     * @param string $Directory
     * @param string $Extension
     * @return bool
     */
    public function DeleteDirectoryFiles(string $Directory, string $Extension)
    {

        if (is_dir($Directory) === true) {

            array_map('unlink', glob($Directory . '*' . $Extension));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete specific file
     *
     * @param string $Directory
     * @param string $FileName
     * @return bool
     */
    public function DeleteFiles(string $Directory, string $FileName)
    {

        if (file_exists($Directory . $FileName) === true) {

            unlink($Directory . $FileName);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cleans up spaces in a string by trimming leading and trailing spaces,
     * and normalizing internal spaces by replacing multiple spaces with a single space.
     *
     * @param string $datas The input string to be cleaned.
     * @return string The cleaned string.
     */
    public function no_space($datas)
    {
        // Trim leading and trailing spaces
        $string = trim($datas);

        // Normalize internal spaces (replace multiple spaces with a single space)
        $string = preg_replace('/\s+/', ' ', $string);

        return $string;
    }

    /**
     * Formar
     */
    public function nbre_format($num, $dec, $separator)
    {

        return $num !== null ? number_format($num, $dec, ',', ' ') : 0;
    }

    /**
     *
     * @param string $inputString|null
     * @return string
     */
    public function reel(?string $inputString = null)
    {

        return str_replace([' ', ','], ['', '.'], $inputString);
    }


    /**
     * Export data in json type
     * @param array|null $datas
     * @return mixed
     */
    public function e_json(?array $datas = [])
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        return json_encode($datas, JSON_PRETTY_PRINT);
    }

    /**
     * Validate an email address securely.
     *
     * @param string $email The email address to validate.
     * @return bool Returns true if the email address is valid, otherwise false.
     */
    public function validateEmail(string $email): bool {
        // Check if the email address is empty or exceeds a reasonable length
        if (empty($email) || strlen($email) > 254) {
            return false;
        }

        // Check if the email address is in a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Split the email address into local part and domain part
        [$localPart, $domainPart] = explode('@', $email, 2);

        // Check if the domain has a valid structure and is not an IP address
        if (!checkdnsrr($domainPart, 'MX') || filter_var($domainPart, FILTER_VALIDATE_IP)) {
            return false;
        }

        // Check if the domain can receive emails (has MX records)
        $mxRecords = [];
        if (!getmxrr($domainPart, $mxRecords)) {
            return false;
        }

        // Check if the local part contains any suspicious characters
        if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $localPart)) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param string $chaines|null
     * @return string
     */
    public function explodeDatas(?string $datas = null, ?string $separator = '', ?int $nbre = 0)
    {

        $chaines = explode($separator, $datas);

        return $chaines[$nbre];
    }

    /**
     * Date format
     */
    public function DateFormat($stringDate)
    {

        // Check if the input date string is empty
        if (empty($stringDate)) {
            return null;
        }

        // Create a DateTime object from the input date string
        $dateTime = date_create($stringDate);

        // Return the formatted date in 'Y-m-d' format
        return $dateTime !== false ? date_format($dateTime, 'Y-m-d') : null;
    }

    /**
     * @return string
     */
    public function strpad($number, $pad_length, $pad_string)
    {
        return str_pad($number, $pad_length, $pad_string, STR_PAD_LEFT);
    }

}