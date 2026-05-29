<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Center;

class CenterPolicy
{
    public function view(User $user, Center $center)
    {
        return $user->centers()->where('center_id', $center->id)->exists();
    }

    public function update(User $user, Center $center)
    {
        return $user->centers()->where('center_id', $center->id)->exists();
    }

    public function manageTeam(User $user, Center $center)
    {
        return $user->centers()->where('center_id', $center->id)->wherePivot('role', 'admin')->exists();
    }
}
