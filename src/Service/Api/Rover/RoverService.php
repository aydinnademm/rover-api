<?php

namespace App\Service\Api\Rover;

use App\Entity\Plateau;
use App\Entity\Rover;
use App\Entity\RoverActivity;
use App\Exception\Api\Rover\RoverNotFoundException;
use App\Exception\Api\Rover\RoverStateNotFoundException;
use App\Exception\Api\Rover\TargetPlateauNotFoundException;
use App\Service\Api\Rover\ParametersResolver\GetParametersResolver;
use App\Service\Api\Rover\ParametersResolver\CreateParametersResolver;
use App\Service\Api\Rover\ParametersResolver\SendCommandParametersResolver;
use App\Service\Rover\RoverApiService;
use App\Traits\Repository\PlateauRepositoryTrait;
use App\Traits\Repository\RoverRepositoryTrait;
use App\Traits\Service\EntityManagerServiceTrait;
use Doctrine\Common\Collections\Collection;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

class RoverService
{
    use EntityManagerServiceTrait;
    use PlateauRepositoryTrait;
    use RoverRepositoryTrait;

    /** @var RoverApiService */
    protected $roverApiService;

    /**
     * @param RoverApiService $roverApiService
     */
    public function __construct(RoverApiService $roverApiService)
    {
        $this->roverApiService = $roverApiService;
    }

    /**
     * @param Request $request
     *
     * @throws RoverNotFoundException
     * @throws RoverStateNotFoundException
     * @throws GuzzleException
     *
     * @return RoverActivity
     */
    public function sendCommandByRequest(Request $request): RoverActivity
    {
        $parameters = (new SendCommandParametersResolver())->resolve($request);

        $rover = $this->getRoverRepository()->find($parameters['rover_id']);

        if (!($rover instanceof Rover)) {
            throw RoverNotFoundException::notFound($parameters['rover_id']);
        }

        $lastActivity = $rover->getActivities()->last();

        if (!($lastActivity instanceof RoverActivity)) {
            throw RoverStateNotFoundException::notFound($parameters['rover_id']);
        }

        $newActivity = clone $lastActivity;

        $newActivity
            ->setRover($rover)
            ->setCommand($parameters['command'])
        ;

        $roverResponse = $this->roverApiService->sendCommand($newActivity);

        $newActivity
            ->setLatitude($roverResponse['latitude'])
            ->setLongitude($roverResponse['longitude'])
            ->setCompassDirection($roverResponse['compass_direction'])
        ;

        $rover->addActivity($newActivity);

        $this->saveToDatabase($rover);

        return $newActivity;
    }

    /**
     * @param Request $request
     *
     * @throws TargetPlateauNotFoundException
     *
     * @return void
     */
    public function createByRequest(Request $request): void
    {
        $parameters = (new CreateParametersResolver())->resolve($request);

        $targetPlateau = $this->getPlateauRepository()->find($parameters['target_plateau_id']);

        if (!($targetPlateau instanceof Plateau)) {
            throw TargetPlateauNotFoundException::notFound($parameters['target_plateau_id']);
        }

        $rover = (new Rover())
            ->setName($parameters['name'])
            ->setIsActive(true)
        ;

        $roverActivity = (new RoverActivity())
            ->setRover($rover)
            ->setPlateau($targetPlateau)
            ->setLatitude($parameters['latitude'])
            ->setLongitude($parameters['longitude'])
            ->setCommand('')
            ->setCompassDirection($parameters['heading_direction'])
        ;

        $rover->addActivity($roverActivity);

        $this->saveToDatabase($rover);
    }

    /**
     * @param Request $request
     *
     * @return Rover
     *
     * @throws RoverNotFoundException
     */
    public function getByRequest(Request $request): Rover
    {
        $parameters = (new GetParametersResolver())->resolve($request);

        $rover = $this->getRoverRepository()->find($parameters['id']);

        if (!($rover instanceof Rover)) {
            throw RoverNotFoundException::notFound($parameters['id']);
        }

        return $rover;
    }

    /**
     * @param Request $request
     *
     * @return RoverActivity
     *
     * @throws RoverNotFoundException
     * @throws RoverStateNotFoundException
     */
    public function getStateByRequest(Request $request): RoverActivity
    {
        $parameters = (new GetParametersResolver())->resolve($request);

        $rover = $this->getRoverRepository()->find($parameters['id']);

        if (!($rover instanceof Rover)) {
            throw RoverNotFoundException::notFound($parameters['id']);
        }

        if (!($rover->getActivities() instanceof Collection) || !($rover->getActivities()->last() instanceof RoverActivity)) {
            throw RoverStateNotFoundException::notFound($parameters['id']);
        }

        return $rover->getActivities()->last();
    }
}