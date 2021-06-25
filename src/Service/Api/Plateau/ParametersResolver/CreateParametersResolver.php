<?php

namespace App\Service\Api\Plateau\ParametersResolver;

use App\Service\Api\ParametersResolver\AbstractParametersResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateParametersResolver extends AbstractParametersResolver
{
    protected function prepareResolver(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'name',
            'latitude',
            'longitude'
        ]);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('latitude', ['string', 'float']);
        $resolver->setAllowedTypes('longitude', ['string', 'float']);
    }
}