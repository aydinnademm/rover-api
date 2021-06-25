<?php

namespace App\Service\Api\Rover\ParametersResolver;

use App\Service\Api\ParametersResolver\AbstractParametersResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateParametersResolver extends AbstractParametersResolver
{
    protected function prepareResolver(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'name',
            'target_plateau_id',
            'latitude',
            'longitude',
            'heading_direction'
        ]);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('target_plateau_id', ['int', 'string']);
        $resolver->setAllowedTypes('latitude', ['string', 'float']);
        $resolver->setAllowedTypes('longitude', ['string', 'float']);
        $resolver->setAllowedTypes('heading_direction', ['string']);
    }
}