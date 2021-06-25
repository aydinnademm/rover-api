<?php

namespace App\Exception\Api\Plateau;

class PlateauAlreadyExistException extends \Exception
{
    public static function exist(string $name): PlateauAlreadyExistException
    {
        $message = "'{$name}' Plateau already exist!";

        return new static($message);
    }
}