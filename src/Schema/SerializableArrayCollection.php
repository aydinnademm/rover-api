<?php

namespace App\Schema;

use Doctrine\Common\Collections\ArrayCollection;

class SerializableArrayCollection extends ArrayCollection implements \JsonSerializable
{
    /**
     * @return array|mixed
     */
    public function jsonSerialize(): array
    {
        return $this->getValues();
    }
}
