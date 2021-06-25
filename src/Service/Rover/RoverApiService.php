<?php

namespace App\Service\Rover;

use App\Entity\RoverActivity;
use App\Exception\Api\Rover\RoverStateNotFoundException;
use App\Traits\JsonTrait;
use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\GuzzleException;

class RoverApiService
{
    protected const COMPASS_DIRECTION_MAP = [
        'N' => [
            'L' => 'W',
            'R' => 'E'
        ],
        'E' => [
            'L' => 'N',
            'R' => 'S'
        ],
        'S' => [
            'L' => 'E',
            'R' => 'W'
        ],
        'W' => [
            'L' => 'S',
            'R' => 'N'
        ]
    ];

    protected const COMPASS_LATITUDE_DIRECTIONS = [
        'W', 'E'
    ];

    protected const COMPASS_LONGITUDE_DIRECTIONS = [
        'N', 'S'
    ];

    use JsonTrait;

    /** @var MockHandler */
    protected $mockHandler;

    /** @var Client */
    protected $mockClient;

    public function __construct()
    {
        $this->mockHandler = new MockHandler();
        $handlerStack     = HandlerStack::create($this->mockHandler);
        $this->mockClient = new Client(['handler' => $handlerStack]);
    }

    /**
     * @param RoverActivity $newActivity
     *
     * @return array
     *
     * @throws RoverStateNotFoundException
     * @throws GuzzleException
     */
    public function sendCommand(RoverActivity $newActivity): array
    {
        $this->mockHandler->reset();

        $lastPosition = $this->getRoverLastPosition($newActivity);

        if (!($lastPosition instanceof RoverActivity)) {
            throw RoverStateNotFoundException::notFound($newActivity->getRover()->getId());
        }

        [$newLatitude, $newLongitude, $newCompassDirection] = $this->calculateNewPosition($lastPosition, $newActivity->getCommand());

        $dummyBody = '{"status": 1, "latitude": "' . $newLatitude . '", "longitude": "' . $newLongitude. '", "compass_direction": "' . $newCompassDirection . '"}';

        $this->mockHandler->append(new Response(200, [], $dummyBody));

        $response = $this->mockClient->request('POST', '/command', [
            'headers' => [
                'username' => 'dummyRoverUsername',
                'password' => 'dummyRoverPassword',
            ],
            'form_params' => [
                'command' => $newActivity->getCommand()
            ]
        ]);

        $responseContent = (string) $response->getBody();
        $responseData    = self::jsonDecode($responseContent);

        return [
            'status' => $responseData['status'],
            'latitude' => $responseData['latitude'],
            'longitude' => $responseData['longitude'],
            'compass_direction' => $responseData['compass_direction']
        ];
    }

    /**
     * @param RoverActivity $lastPosition
     * @param string        $command
     *
     * @return array
     */
    protected function calculateNewPosition(RoverActivity $lastPosition, string $command): array
    {
        $directions = array_map('trim', array_filter(str_split($command)));

        $lastCompassDirection = $lastPosition->getCompassDirection();

        $newLatitude  = $lastPosition->getLatitude();
        $newLongitude = $lastPosition->getLongitude();

        foreach ($directions as $direction) {
            if (in_array($direction, ['L', 'R'])) {
                $newCompassDirection  = self::COMPASS_DIRECTION_MAP[$lastCompassDirection][$direction];
                $lastCompassDirection = $newCompassDirection;
            }

            if ($direction === 'M') {
                if (in_array($lastCompassDirection, self::COMPASS_LATITUDE_DIRECTIONS)) {
                    $newLatitude += 1;
                }

                if (in_array($lastCompassDirection, self::COMPASS_LONGITUDE_DIRECTIONS)) {
                    $newLongitude += 1;
                }
            }
        }

        return [$newLatitude, $newLongitude, $lastCompassDirection];
    }

    /**
     * @param RoverActivity $newActivity
     *
     * @return RoverActivity|null
     */
    protected function getRoverLastPosition(RoverActivity $newActivity): ?RoverActivity
    {
        $rover = $newActivity->getRover();

        $array = $rover->getActivities()->toArray();

        rsort($array);

        $roverActivities = new ArrayCollection($array);

        foreach ($roverActivities as $roverActivity) {
            if ($roverActivity !== $newActivity) {
                return $roverActivity;
            }
        }

        return null;
    }
}