<?php

namespace App\Traits\Repository;

use App\Repository\PlateauRepository;

trait PlateauRepositoryTrait
{
    /** @var PlateauRepository */
    protected $_plateauRepository;

    /**
     * @return PlateauRepository
     */
    public function getPlateauRepository(): PlateauRepository
    {
        return $this->_plateauRepository;
    }

    /**
     * @Required
     *
     * @param PlateauRepository $plateauRepository
     *
     * @return self
     */
    public function setPlateauRepository(PlateauRepository $plateauRepository): self
    {
        $this->_plateauRepository = $plateauRepository;

        return $this;
    }
}