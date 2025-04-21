<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\TravelRequestRepository;
use App\Http\Requests\ChangeStatusTravelRequest;
use App\Http\Requests\StoreTravelRequestRequest;
use App\Http\Requests\UpdateTravelRequestRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TravelRequestController extends Controller
{
    /**
     * @param TravelRequestRepository $travelRequestRepository
     */
    public function __construct(
        protected TravelRequestRepository $travelRequestRepository
    )
    {
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        try {

            return response([
                'travelRequests' => $this->travelRequestRepository->listByUser(),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao listar pedidos: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao listar pedidos.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StoreTravelRequestRequest $request
     * @return Response
     */
    public function store(StoreTravelRequestRequest $request): Response
    {
        try {

            return response([
                'travelRequests' => $this->travelRequestRepository->store($request->validated()),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao criar pedido: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao criar pedido.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
