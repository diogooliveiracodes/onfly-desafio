<?php

namespace App\Http\Controllers;

use App\Models\TravelRequestNotification;
use App\Repositories\TravelRequestNotificationRepository;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Log;

class TravelRequestNotificationController extends Controller
{
    public function __construct(
        protected TravelRequestNotificationRepository $repository
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
                'notifications' => $this->repository->listByUser(),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar notificações: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao buscar notificações.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TravelRequestNotification $travelRequestNotification
     * @return Response
     */
    public function markAsRead(TravelRequestNotification $travelRequestNotification): Response
    {
        try {

            return response([
                'notification' => $this->repository->markAsRead($travelRequestNotification),
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar notificação: ' . $e->getMessage());

            return response([
                'error' => 'Erro ao atualizar notificação.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
