<?php

namespace App\Repositories;

use App\Enums\TravelStatus;
use App\Enums\UserRoles;
use App\Models\TravelRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TravelRequestRepository
{
    public function __construct(protected TravelRequest $model) {}

    /**
     * @return Collection
     */
    public function listByUser(): Collection
    {
        return $this->model->where('user_id', Auth::id())
            ->select([
                'id',
                'requester_name',
                'destination',
                'departure_date',
                'return_date',
                'status',
                'created_at',
                'updated_at'
            ])
            ->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data): TravelRequest
    {
        return $this->model->create([
            'user_id' => Auth::id(),
            'requester_name' => $data['requester_name'] ?? Auth::user()->name,
            'destination' => $data['destination'],
            'departure_date' => Carbon::parse($data['departure_date'])->format('Y-m-d'),
            'return_date' => Carbon::parse($data['return_date'])->format('Y-m-d'),
            'status' => TravelStatus::SOLICITADO,
        ]);
    }

    /**
     * @param array $data
     * @param TravelRequest $travelRequest
     * @return TravelRequest
     */
    public function update(array $data, TravelRequest $travelRequest): TravelRequest
    {
        $travelRequest->update([
            'requester_name' => $data['requester_name'],
            'destination' => $data['destination'],
            'departure_date' => Carbon::parse($data['departure_date'])->format('Y-m-d'),
            'return_date' => Carbon::parse($data['return_date'])->format('Y-m-d')
        ]);

        return $travelRequest;
    }

    /**
     * @param TravelRequest $travelRequest
     * @param int $status
     * @return TravelRequest
     */
    public function changeStatus(TravelRequest $travelRequest, int $status): TravelRequest
    {
        $travelRequest->update([
            'status' => $status
        ]);

        return $travelRequest;
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function search(array $data): Collection
    {
        $query = $this->model->query()
            ->select([
                'id',
                'requester_name',
                'destination',
                'departure_date',
                'return_date',
                'status',
                'created_at',
                'updated_at'
            ]);

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if (isset($data['destination'])) {
            $query->where('destination', 'like', '%' . $data['destination'] . '%');
        }

        if (!empty($data['start']) && empty($data['end'])) {
            $start = Carbon::parse($data['start'])->startOfDay();
            $query->whereDate('departure_date', '>=', $start)
                ->orWhereDate('return_date', '>=', $start);
        }

        if (!empty($data['end']) && empty($data['start'])) {
            $start = Carbon::parse($data['end'])->startOfDay();
            $query->whereDate('return_date', '<=', $start)
                ->orWhereDate('departure_date', '<=', $start);
        }

        if (!empty($data['start']) && !empty($data['end'])) {
            $start = Carbon::parse($data['start'])->startOfDay();
            $end = Carbon::parse($data['end'])->endOfDay();

            $query->where(function ($q) use ($start, $end) {
                $q->whereDate('departure_date', '<=', $end)
                    ->whereDate('return_date', '>=', $start);
            });
        }

        return $query->orderByDesc('created_at')
            ->get();
    }
}
