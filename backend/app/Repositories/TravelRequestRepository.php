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

}
