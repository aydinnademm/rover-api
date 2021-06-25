<?php

namespace App\Exception\Api\Rover;

class RoverNotFoundException extends \Exception
{
    public static function notFound($id): RoverNotFoundException
    {
        $message = "'{$id}' Rover not found!";

        return new static($message);
    }
}