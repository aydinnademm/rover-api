<?php

namespace App\Traits;

use Doctrine\Common\Collections\Collection;

trait JsonSerializeTrait
{
    public static $FLOAT_PRECISION = 2;

    public static $_IGNORE_PREFIX = '_';

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $variables = get_object_vars($this);

        $result = [];

        foreach ($variables as $key => $value) {
            if (strpos($key, self::$_IGNORE_PREFIX) === 0) {
                continue;
            }

            if ($value instanceof Collection) {
                $value = $value->toArray();
            }

            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if (is_float($value)) {
                $value = round($value, self::$FLOAT_PRECISION, PHP_ROUND_HALF_UP);
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
