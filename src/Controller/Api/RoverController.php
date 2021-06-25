<?php

namespace App\Controller\Api;

use App\Service\Api\Rover\RoverService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/rover")
 */
class RoverController extends AbstractController
{
    /**
     * @Route("/send-command.json", name="rover_send_command")
     *
     * @param Request      $request
     * @param RoverService $roverService
     *
     * @return JsonResponse
     */
    public function sendCommand(Request $request, RoverService $roverService): JsonResponse
    {
        try {
            $roverState = $roverService->sendCommandByRequest($request);

            return $this->json([
                'status' => true,
                'data' => $roverState->jsonSerialize()
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }

    /**
     * @Route("/get-state.json", name="rover_get_state")
     *
     * @param Request      $request
     * @param RoverService $roverService
     *
     * @return JsonResponse
     */
    public function getState(Request $request, RoverService $roverService): JsonResponse
    {
        try {
            $roverState = $roverService->getStateByRequest($request);

            return $this->json([
                'status' => true,
                'data' => $roverState->jsonSerialize()
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }

    /**
     * @Route("/get.json", name="rover_get")
     *
     * @param Request      $request
     * @param RoverService $roverService
     *
     * @return JsonResponse
     */
    public function getRover(Request $request, RoverService $roverService): JsonResponse
    {
        try {
            $rover = $roverService->getByRequest($request);

            return $this->json([
                'status' => true,
                'data' => $rover->jsonSerialize()
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }

    /**
     * @Route("/create.json", name="rover_create")
     *
     * @param Request      $request
     * @param RoverService $roverService
     *
     * @return JsonResponse
     */
    public function create(Request $request, RoverService $roverService): JsonResponse
    {
        try {
            $roverService->createByRequest($request);

            return $this->json([
                'status' => true,
                'message' => 'Rover create successful.'
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }
}
