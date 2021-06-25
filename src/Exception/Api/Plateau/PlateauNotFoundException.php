<?php

namespace App\Exception\Api\Plateau;

class PlateauNotFoundException extends \Exception
{
    public static function notFound($id): PlateauNotFoundException
    {
        $message = "'{$id}' Plateau not found!";

        return new static($message);
    }
}