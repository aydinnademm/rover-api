<?php

namespace App\Exception\Api\Rover;

class TargetPlateauNotFoundException extends \Exception
{
    public static function notFound($id): TargetPlateauNotFoundException
    {
        $message = "'{$id}' Target plateau not found!";

        return new static($message);
    }
}