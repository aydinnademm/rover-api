<?php

namespace App\Traits\Repository;

use App\Repository\RoverRepository;

trait RoverRepositoryTrait
{
    /** @var RoverRepository */
    protected $_roverRepository;

    /**
     * @return RoverRepository
     */
    public function getRoverRepository(): RoverRepository
    {
        return $this->_roverRepository;
    }

    /**
     * @Required
     *
     * @param RoverRepository $roverRepository
     *
     * @return self
     */
    public function setRoverRepository(RoverRepository $roverRepository): self
    {
        $this->_roverRepository = $roverRepository;
        return $this;
    }
}