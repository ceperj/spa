<?php

use Carbon\Carbon;

if (!function_exists('remove_accents')) {
    /**
     * Replace accents from a string by its corresponding unaccented characters.
     */
    function remove_accents($string)
    {
        $replacement_table = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ý' => 'Y',
            'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ü' => 'U',
            'Ã' => 'A', 'Õ' => 'O', 'Ç' => 'C', 'Ñ' => 'N',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ý' => 'y',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'ã' => 'a', 'õ' => 'o', 'ç' => 'c', 'ñ' => 'n', 'ÿ' => 'y',
        ];
        return strtr($string, $replacement_table);
    }
}

if (!function_exists('layout_file_repeat')) {
    /**
     * Repeat a character to the size of a string. Similar to str_repeat, but
     * requires exactly one character.
     * 
     * @param string $char  The character to repeat. Must not be empty ('').
     * @param int    $size  The exact size of the resulting string.
     * @return string       The resulting string.
     */
    function layout_file_repeat($char, $size)
    {
        return str_repeat($char[0], $size);
    }
}

if (!function_exists('layout_file_alpha')) {
    /**
     * Returns a sequence of unaccented characters matching exactly the size.
     * 
     * @param string $text  The text to clip.
     * @param int    $size  The exact size of the resulting string.
     * @return string       The text matching the size
     */
    function layout_file_alpha($text, $size)
    {
        if (mb_strlen($text) > $size)
            return strtoupper(remove_accents(mb_substr($text, 0, $size)));

        return str_pad(strtoupper(remove_accents($text)), $size, ' ', STR_PAD_RIGHT);
    }
}

if (!function_exists('layout_file_number')) {
    /**
     * Returns a string with a sequence of numbers matching the size.
     * 
     * @param int? $number  The number as string or int, or null for white spaces.
     * @param int  $size    The exact size of the resulting string.
     * @return string       The resulting string of the exact size. If $number is null, it will be filled with white spaces.
     */
    function layout_file_number($number, $size)
    {
        if (! preg_match('/0+/', "$number") && ! $number)
            return str_repeat(' ', $size);

        $text = "$number";
        if (mb_strlen($text) > $size)
            return mb_substr($text, -$size);
        
        return str_pad($text, $size, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('layout_file_date_yyyy')) {
    /**
     * Returns a string with the given length for a date YYYY(MM)(DD) field.
     * 
     * @param string|array|Carbon|null $date    A date.
     * @param int $size                         Size of the string.
     * @return string                           A string with the exact size.
     */
    function layout_file_date_yyyy($date, $size)
    {
        if (! $date)
            return str_repeat(' ', $size);

        if (is_string($date))
        {
            $length = strlen($date);
            if ($length !== $size)
                throw new Exception("Value '$date' is not a valid date, length ($length) does not match ($size).");
            return $date;
        }

        if (is_array($date))
        {
            if ($size === 8)
                return layout_file_number($date['y'], 4)
                . layout_file_number($date['m'], 2)
                . layout_file_number($date['d'], 2);
            if ($size === 6)
                return layout_file_number($date['y'], 4)
                . layout_file_number($date['m'], 2);
            if ($size === 4)
                return layout_file_number($date['y'], 4);
            throw new Exception("Date size must be 8 (YYYYMMDD), 6 (YYYYMM) or 4 (YYYY).");
        }

        if ($date instanceof Carbon){
            return layout_file_date_yyyy([
                'y' => $date->year,
                'm'=>$date->month,
                'd'=>$date->day
            ], $size);
        }

        throw new Exception("Date is not valid, provided value was not string, array or Carbon instance.");
    }
}
