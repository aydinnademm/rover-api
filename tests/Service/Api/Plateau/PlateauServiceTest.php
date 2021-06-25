<?php

namespace App\Tests\Service\Api\Plateau;

use App\Schema\SerializableArrayCollection;
use App\Service\Api\Plateau\PlateauService;
use App\Tests\Service\Api\Plateau\MockBuilder\RequestBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class PlateauServiceTest extends KernelTestCase
{
    /** @var PlateauService */
    private static $plateauService;

    /** @var RequestBuilder */
    private static $requestBuilder;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$plateauService = self::$container->get('App\Service\Api\Plateau\PlateauService');
        self::$requestBuilder = new RequestBuilder();
    }

    public function testGetByRequestForValidRequest()
    {
        $this->testCreateByRequestForValidRequest();

        $plateauList = self::$plateauService->getList();

        Assert::assertSame(SerializableArrayCollection::class, get_class($plateauList));
        Assert::assertGreaterThan(0, $plateauList->count());
    }

    public function testCreateByRequestForValidRequest()
    {
        $validRequest = self::$requestBuilder->buildRequestForParameters([
            'name'      => 'Test plateau ' . uniqid('r'),
            'latitude'  => 104.55,
            'longitude' => 104.55,
        ]);

        $result = null;

        try {
            $result = self::$plateauService->createByRequest($validRequest);
        } catch (\Throwable $exception) {
            Assert::assertTrue(false);
        }

        Assert::assertNull($result);
    }

    public function testCreateByRequestForInValidRequest()
    {
        $inValidRequest = self::$requestBuilder->buildRequestForParameters(['name' => "Rover 1"]);

        $result = null;

        try {
            $result = self::$plateauService->createByRequest($inValidRequest);

            Assert::assertTrue(false);
        } catch (\Throwable $exception) {
            Assert::assertInstanceOf(MissingOptionsException::class, $exception);
        }

        Assert::assertNull($result);
    }

    public function testGetByRequestForInValidRequest()
    {
        $inValidRequest = self::$requestBuilder->buildRequestForParameters(['idx' => 1]);

        $result = null;

        try {
            $result = self::$plateauService->getByRequest($inValidRequest);

            Assert::assertTrue(false);
        } catch (\Throwable $exception) {
            Assert::assertInstanceOf(UndefinedOptionsException::class, $exception);
        }

        Assert::assertNull($result);
    }
}