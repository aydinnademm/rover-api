<?php

namespace App\Service\Api\Plateau;

use App\Entity\Plateau;
use App\Exception\Api\Plateau\PlateauAlreadyExistException;
use App\Exception\Api\Plateau\PlateauNotFoundException;
use App\Library\SlugIfy;
use App\Schema\SerializableArrayCollection;
use App\Service\Api\Plateau\ParametersResolver\CreateParametersResolver;
use App\Service\Api\Plateau\ParametersResolver\GetParametersResolver;
use App\Traits\Repository\PlateauRepositoryTrait;
use App\Traits\Service\EntityManagerServiceTrait;
use Symfony\Component\HttpFoundation\Request;

class PlateauService
{
    use PlateauRepositoryTrait;
    use EntityManagerServiceTrait;

    /**
     * @param Request $request
     *
     * @return void
     *
     * @throws PlateauAlreadyExistException
     */
    public function createByRequest(Request $request): void
    {
        $parameters = (new CreateParametersResolver())->resolve($request);

        $name = $parameters['name'];
        $slug = SlugIfy::slugIfy($parameters['name']);

        $existPlateau = $this->getPlateauRepository()->findOneBy([
            'slug' => $slug
        ]);

        if ($existPlateau instanceof Plateau) {
            throw PlateauAlreadyExistException::exist($name);
        }

        $plateau = (new Plateau())
            ->setName($name)
            ->setLatitude($parameters['latitude'])
            ->setLongitude($parameters['longitude'])
            ->setIsActive(true)
        ;

        $this->saveToDatabase($plateau);
    }

    /**
     * @param Request $request
     *
     * @return Plateau
     *
     * @throws PlateauNotFoundException
     */
    public function getByRequest(Request $request): Plateau
    {
        $parameters = (new GetParametersResolver())->resolve($request);

        $plateau = $this->getPlateauRepository()->find($parameters['id']);

        if (!($plateau instanceof Plateau)) {
            throw PlateauNotFoundException::notFound($parameters['id']);
        }

        return $plateau;
    }

    /**
     * @return SerializableArrayCollection
     */
    public function getList(): SerializableArrayCollection
    {
        return new SerializableArrayCollection($this->getPlateauRepository()->findAll());
    }
}