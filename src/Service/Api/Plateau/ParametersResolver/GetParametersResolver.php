<?php

namespace App\Service\Api\Plateau\ParametersResolver;

use App\Service\Api\ParametersResolver\AbstractParametersResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GetParametersResolver extends AbstractParametersResolver
{
    protected function prepareResolver(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'id'
        ]);

        $resolver->setAllowedTypes('id', ['int', 'string']);
    }
}