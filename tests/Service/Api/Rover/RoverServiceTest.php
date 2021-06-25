<?php

namespace App\Tests\Service\Api\Rover;

use App\Entity\Rover;
use App\Entity\RoverActivity;
use App\Exception\Api\Rover\RoverNotFoundException;
use App\Service\Api\Rover\RoverService;
use App\Tests\Service\Api\Plateau\PlateauServiceTest;
use App\Tests\Service\Api\Rover\MockBuilder\RequestBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class RoverServiceTest extends KernelTestCase
{
    /** @var RoverService */
    private static $roverService;

    /** @var RequestBuilder */
    private static $requestBuilder;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$roverService = self::$container->get('App\Service\Api\Rover\RoverService');
        self::$requestBuilder = new RequestBuilder();
    }

    public function testSendCommandByRequestForValid()
    {
        self::testCreateByRequestForValidRequest();

        $validRequestForGetState = self::$requestBuilder->buildRequestForParameters(['id' => 1]);

        $roverStartPosition = self::$roverService->getStateByRequest($validRequestForGetState);

        $validRequestForSendCommand = self::$requestBuilder->buildRequestForParameters([
            'rover_id' => 1,
            'command' => 'LM'
        ]);

        $newRoverPosition = self::$roverService->sendCommandByRequest($validRequestForSendCommand);

        if (in_array($roverStartPosition->getCompassDirection(), ['N', 'S'])) {
            Assert::assertSame(($roverStartPosition->getLatitude() + 1), $newRoverPosition->getLatitude());
        }

        if (in_array($roverStartPosition->getCompassDirection(), ['W', 'E'])) {
            Assert::assertSame(($roverStartPosition->getLongitude() + 1), $newRoverPosition->getLongitude());
        }
    }

    public function testSendCommandByRequestForValidCase2()
    {
        self::testCreateByRequestForValidRequest();

        $validRequestForGetState = self::$requestBuilder->buildRequestForParameters(['id' => 1]);

        $roverStartPosition = self::$roverService->getStateByRequest($validRequestForGetState);

        $validRequestForSendCommand = self::$requestBuilder->buildRequestForParameters([
            'rover_id' => 1,
            'command' => 'LMMMM'
        ]);

        $newRoverPosition = self::$roverService->sendCommandByRequest($validRequestForSendCommand);

        if (in_array($roverStartPosition->getCompassDirection(), ['N', 'S'])) {
            Assert::assertSame(($roverStartPosition->getLatitude() + 4), $newRoverPosition->getLatitude());
        }

        if (in_array($roverStartPosition->getCompassDirection(), ['W', 'E'])) {
            Assert::assertSame(($roverStartPosition->getLongitude() + 4), $newRoverPosition->getLongitude());
        }
    }

    public function testSendCommandByRequestForValidCase3()
    {
        self::testCreateByRequestForValidRequest();

        $validRequestForGetState = self::$requestBuilder->buildRequestForParameters(['id' => 1]);

        $roverStartPosition = self::$roverService->getStateByRequest($validRequestForGetState);

        $validRequestForSendCommand = self::$requestBuilder->buildRequestForParameters([
            'rover_id' => 1,
            'command' => 'LMLMLM'
        ]);

        $newRoverPosition = self::$roverService->sendCommandByRequest($validRequestForSendCommand);

        if (in_array($roverStartPosition->getCompassDirection(), ['N', 'S'])) {
            Assert::assertSame(($roverStartPosition->getLatitude() + 2), $newRoverPosition->getLatitude());
            Assert::assertSame(($roverStartPosition->getLongitude() + 1), $newRoverPosition->getLongitude());
        }

        if (in_array($roverStartPosition->getCompassDirection(), ['W', 'E'])) {
            Assert::assertSame(($roverStartPosition->getLongitude() + 2), $newRoverPosition->getLongitude());
            Assert::assertSame(($roverStartPosition->getLatitude() + 1), $newRoverPosition->getLatitude());
        }
    }

    public function testGetStateByRequestForException()
    {
        self::testCreateByRequestForValidRequest();

        $inValidRequest = self::$requestBuilder->buildRequestForParameters(['id' => 10000]);

        $result = null;

        try {
            $result = self::$roverService->getStateByRequest($inValidRequest);

            Assert::assertTrue(false);
        } catch (\Throwable $exception) {
            Assert::assertSame(RoverNotFoundException::class, get_class($exception));

            Assert::assertTrue(true);
        }

        Assert::assertNull($result);
    }

    public function testGetStateByRequestForValidRequest()
    {
        self::testCreateByRequestForValidRequest();

        $validRequest = self::$requestBuilder->buildRequestForParameters(['id' => 1]);

        $result = null;

        try {
            $result = self::$roverService->getStateByRequest($validRequest);

            Assert::assertSame(RoverActivity::class, get_class($result));
            Assert::assertSame(1, $result->getRover()->getId());
        } catch (\Throwable $exception) {
            Assert::assertTrue(false);
        }
    }

    public function testGetByRequestForException()
    {
        self::testCreateByRequestForValidRequest();

        $inValidRequest = self::$requestBuilder->buildRequestForParameters(['id' => 100100]);

        $result = null;

        try {
            $result = self::$roverService->getByRequest($inValidRequest);

            Assert::assertTrue(false);
        } catch (\Throwable $exception) {
            Assert::assertSame(RoverNotFoundException::class, get_class($exception));
            Assert::assertTrue(true);
        }

        Assert::assertNull($result);
    }

    public function testGetByRequestForValidRequest()
    {
        self::testCreateByRequestForValidRequest();

        $validRequest = self::$requestBuilder->buildRequestForParameters(['id' => 1]);

        $result = null;

        try {
            $result = self::$roverService->getByRequest($validRequest);

            Assert::assertSame(Rover::class, get_class($result));
            Assert::assertSame(1, $result->getId());
        } catch (\Throwable $exception) {
            Assert::assertTrue(false);
        }
    }

    public function testCreateByRequestForValidRequest()
    {
        (new PlateauServiceTest)->testCreateByRequestForValidRequest();

        $inValidRequest = self::$requestBuilder->buildRequestForParameters([
            'name' => 'Rover ' . uniqid('r'),
            'target_plateau_id' => 1,
            'latitude' => 303.987,
            'longitude' => 307.987,
            'heading_direction' => 'S'
        ]);

        $result = null;

        try {
            self::$roverService->createByRequest($inValidRequest);

            Assert::assertTrue(true);
        } catch (\Throwable $exception) {
            Assert::assertTrue(false);
        }

    }

    public function testCreateByRequestForInValidRequest()
    {
        $inValidRequest = self::$requestBuilder->buildRequestForParameters(['name' => 'Rover 1', 'target_plateau_id' => 1]);

        $result = null;

        try {
            $result = self::$roverService->createByRequest($inValidRequest);

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
            $result = self::$roverService->getByRequest($inValidRequest);

            Assert::assertTrue(false);
        } catch (\Throwable $exception) {
            Assert::assertInstanceOf(UndefinedOptionsException::class, $exception);
        }

        Assert::assertNull($result);
    }
}