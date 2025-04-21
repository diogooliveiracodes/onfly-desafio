<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\SearchTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Repositories\TravelRequestRepository;
use App\Http\Requests\ChangeStatusTravelRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Repositories\TravelRequestNotificationRepository;

class TravelRequestController extends Controller
{
    /**
     * @param TravelRequestRepository $travelRequestRepository
     * @param TravelRequestNotificationRepository $travelRequestNotificationRepository
     */
    public function __construct(
        protected TravelRequestRepository $travelRequestRepository,
        protected TravelRequestNotificationRepository $travelRequestNotificationRepository
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
     * @param StoreTravelRequest $request
     * @return Response
     */
    public function store(StoreTravelRequest $request): Response
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
     * @param UpdateTravelRequest $request
     * @param TravelRequest $travelRequest
     * @return Response
     */
    public function update(UpdateTravelRequest $request, TravelRequest $travelRequest): Response
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
            $this->travelRequestNotificationRepository->create([
                'travel_request_id' => $travelRequest->id,
                'user_id' => $travelRequest->user_id,
                'old_status' => $travelRequest->getOriginal('status'),
                'new_status' => $request->input('status'),
            ]);

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

    /**
     * @param SearchTravelRequest $request
     * @return Response
     */
    public function search(SearchTravelRequest $request): Response
    {
        try {

            return response([
                'travelRequests' => $this->travelRequestRepository->search($request->validated()),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar pedidos: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao buscar pedidos.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
