<?php

namespace App\Tests\Service\Api\Rover\MockBuilder;

use Symfony\Component\HttpFoundation\Request;

class RequestBuilder
{
    /** @var Request */
    protected $request;

    public function buildBaseRequest(): Request
    {
        $request = new Request();

        $request->setMethod('POST');

        $this->request = $request;

        return $request;
    }

    public function buildRequestForParameters(array $requestParameters): Request
    {
        $request = new Request([], [], [], [], [], [], json_encode($requestParameters, true));

        $request->setMethod('POST');

        $this->request = $request;

        return $request;
    }
}