<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelRequestNotification;

class TravelRequestNotificationRepository
{
    protected TravelRequestNotification $model;

    public function __construct(TravelRequestNotification $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function listByUser(): Collection
    {
        return $this->model
            ->where('user_id', Auth::user()->id)
            ->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param array $data
     * @return TravelRequestNotification
     */
    public function create(array $data): TravelRequestNotification
    {
        return $this->model->create([
            'travel_request_id' => $data['travel_request_id'],
            'user_id' => $data['user_id'],
            'old_status' => $data['old_status'] ?? null,
            'new_status' => $data['new_status'],
        ]);
    }

    /**
     * @param TravelRequestNotification $travelRequestNotification
     * @return TravelRequestNotification
     */
    public function markAsRead(TravelRequestNotification $travelRequestNotification): TravelRequestNotification
    {
        $travelRequestNotification->update(['read_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        return $travelRequestNotification;
    }
}
