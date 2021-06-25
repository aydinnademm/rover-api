<?php

namespace App\Service\Api\Rover\ParametersResolver;

use App\Service\Api\ParametersResolver\AbstractParametersResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendCommandParametersResolver extends AbstractParametersResolver
{
    protected function prepareResolver(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'rover_id',
            'command'
        ]);

        $resolver->setAllowedTypes('rover_id', ['int', 'string']);
        $resolver->setAllowedTypes('command', 'string');
    }
}