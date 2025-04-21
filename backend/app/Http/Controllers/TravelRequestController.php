<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Repositories\TravelRequestRepository;
use App\Http\Requests\ChangeStatusTravelRequest;
use App\Http\Requests\StoreTravelRequestRequest;
use App\Http\Requests\UpdateTravelRequestRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TravelRequestController extends Controller
{
    /**
     * @param TravelRequestRepository $travelRequestRepository
     */
    public function __construct(
        protected TravelRequestRepository $travelRequestRepository
    ) {}

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

    /**
     * @param TravelRequest $travelRequest
     * @return Response
     */
    public function show(TravelRequest $travelRequest): Response
    {
        try {
            Gate::authorize('view', $travelRequest);

            return response([
                'travelRequest' => $travelRequest,
            ], ResponseAlias::HTTP_OK);
        } catch (AuthorizationException $e) {

            return response([
                'error' => 'Você não tem permissão para visualizar este pedido.',
            ], ResponseAlias::HTTP_FORBIDDEN);
        } catch (\Throwable $e) {
            Log::error('Erro ao listar pedido: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao listar pedido.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param UpdateTravelRequestRequest $request
     * @param TravelRequest $travelRequest
     * @return Response
     */
    public function update(UpdateTravelRequestRequest $request, TravelRequest $travelRequest): Response
    {
        try {
            Gate::authorize('update', $travelRequest);

            return response([
                'travelRequest' => $this->travelRequestRepository->update($request->validated(), $travelRequest),
            ], ResponseAlias::HTTP_OK);
        } catch (AuthorizationException $e) {

            return response([
                'error' => 'Você não tem permissão para atualizar este pedido.',
            ], ResponseAlias::HTTP_FORBIDDEN);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar pedido: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao atualizar pedido.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param ChangeStatusTravelRequest $request
     * @param TravelRequest $travelRequest
     * @return Response
     */
    public function changeStatus(ChangeStatusTravelRequest $request, TravelRequest $travelRequest): Response
    {
        try {

            return response([
                'travelRequest' => $this->travelRequestRepository->changeStatus($travelRequest, $request->input('status')),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar pedido: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao atualizar pedido.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
