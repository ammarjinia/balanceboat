<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Experience;

class ExperiencePolicy
{
    public function view(User $user, Experience $experience)
    {
        return $user->centers()->where('center_id', $experience->center_id)->exists();
    }

    public function create(User $user)
    {
        return $user->getPrimaryCenter() !== null;
    }

    public function update(User $user, Experience $experience)
    {
        return $user->centers()->where('center_id', $experience->center_id)->exists();
    }

    public function delete(User $user, Experience $experience)
    {
        return $user->centers()->where('center_id', $experience->center_id)->exists();
    }

    public function publish(User $user, Experience $experience)
    {
        return $this->update($user, $experience);
    }
}
