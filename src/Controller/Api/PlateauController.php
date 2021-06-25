<?php

namespace App\Controller\Api;

use App\Service\Api\Plateau\PlateauService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/plateau")
 */
class PlateauController extends AbstractController
{
    /**
     * @Route("/get.json", name="plateau_get")
     *
     * @param Request        $request
     * @param PlateauService $plateauService
     *
     * @return JsonResponse
     */
    public function getPlateau(Request $request, PlateauService $plateauService): JsonResponse
    {
        try {
            $plateau = $plateauService->getByRequest($request);

            return $this->json([
                'status' => true,
                'data' => $plateau->jsonSerialize()
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }

    /**
     * @Route("/list.json", name="plateau_list")
     *
     * @param Request        $request
     * @param PlateauService $plateauService
     *
     * @return JsonResponse
     */
    public function list(Request $request, PlateauService $plateauService): JsonResponse
    {
        try {
            $list = $plateauService->getList();

            return $this->json([
                'status' => true,
                'data' => $list->jsonSerialize()
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }

    /**
     * @Route("/create.json", name="plateau_create")
     *
     * @param Request        $request
     * @param PlateauService $plateauService
     *
     * @return JsonResponse
     */
    public function create(Request $request, PlateauService $plateauService): JsonResponse
    {
        try {
            $plateauService->createByRequest($request);

            return $this->json([
                'status' => true,
                'message' => 'Plateau create successful.'
            ]);
        } catch (\Throwable $throwable) {
            return $this->json([
                'status' => false,
                'message' => 'Something went wrong! ' . $throwable->getMessage()
            ]);
        }
    }
}
