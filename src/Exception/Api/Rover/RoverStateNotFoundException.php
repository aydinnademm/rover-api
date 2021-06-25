<?php

namespace App\Exception\Api\Rover;

class RoverStateNotFoundException extends \Exception
{
    public static function notFound($id): RoverStateNotFoundException
    {
        $message = "'{$id}' Rover state not found!";

        return new static($message);
    }
}