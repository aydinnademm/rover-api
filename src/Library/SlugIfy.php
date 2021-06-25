<?php

namespace App\Library;


class SlugIfy
{
    /**
     * @param string $text
     * @return string
     */
    public static function slugIfy(string $text): string
    {
        $find = ['Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#'];

        $replace = ['c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp'];

        $text = strtolower(str_replace($find, $replace, $text));

        $text = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $text);

        $text = str_replace(".", ' ', $text);

        $text = trim(preg_replace('/\s+/', ' ', $text));

        $text = str_replace(' ', '-', $text);

        $textArray = str_split($text);

        $newStrArray = [];
        foreach ($textArray as $index => $value) {
            if ((end($newStrArray) === '-') && ($value === '-')) {
                continue;
            }

            $newStrArray[] = $value;
        }

        return implode('', $newStrArray);
    }
}