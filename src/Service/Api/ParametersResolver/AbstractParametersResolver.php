<?php

namespace App\Service\Api\ParametersResolver;

use App\Traits\JsonTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractParametersResolver
{
    use JsonTrait;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function resolve(Request $request): array
    {
        $parameters = $request->getContent();

        if (is_string($parameters)) {
            $parameters = self::jsonDecode($parameters);
        }

        if (!is_array($parameters)) {
            throw new \UnexpectedValueException('The value to be resolved must either be a json object or an array. Other values are not acceptable!');
        }

        $resolver = new OptionsResolver();

        $this->prepareResolver($resolver);

        return $resolver->resolve($parameters);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    abstract protected function prepareResolver(OptionsResolver $resolver): void;
}
