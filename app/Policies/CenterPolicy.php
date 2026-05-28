<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Center;

class CenterPolicy
{
    public function view(User $user, Center $center): bool
    {
        return $user->hasCenter($center->id);
    }

    public function update(User $user, Center $center): bool
    {
        return $user->hasCenter($center->id) &&
            $user->centers()
            ->wherePivot('role', 'admin')
            ->where('id', $center->id)
            ->exists();
    }

    public function manageTeam(User $user, Center $center): bool
    {
        return $this->update($user, $center);
    }
}
