<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Experience;

class ExperiencePolicy
{
    public function view(User $user, Experience $experience): bool
    {
        return $user->hasCenter($experience->center_id) ||
            $user->centers()->where('id', $experience->center_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->centers()->exists();
    }

    public function update(User $user, Experience $experience): bool
    {
        return $user->hasCenter($experience->center_id);
    }

    public function delete(User $user, Experience $experience): bool
    {
        return $user->hasCenter($experience->center_id);
    }

    public function publish(User $user, Experience $experience): bool
    {
        return $user->hasCenter($experience->center_id);
    }
}
