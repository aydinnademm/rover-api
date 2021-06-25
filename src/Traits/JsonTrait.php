<?php

namespace App\Traits;

trait JsonTrait
{
    /**
     * @param string $json
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     */
    public static function jsonDecode(string $json): array
    {
        $array = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \UnexpectedValueException('JSON object decode error! Error message: ' . json_last_error_msg());
        }

        return $array;
    }
}