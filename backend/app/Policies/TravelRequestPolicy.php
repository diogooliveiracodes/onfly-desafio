<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;

class TravelRequestPolicy
{
    public function view(User $user, TravelRequest $travelRequest): bool
    {
        return $user->isAdmin() || $user->id === $travelRequest->user_id;
    }

    public function update(User $user, TravelRequest $travelRequest): bool
    {
        return
            $user->id === $travelRequest->user_id
            && !$travelRequest->isProcessed();
    }
}
