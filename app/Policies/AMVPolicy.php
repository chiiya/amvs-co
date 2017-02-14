<?php

namespace App\Policies;

use App\User;
use App\AMV;
use Illuminate\Auth\Access\HandlesAuthorization;

class AMVPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the AMV.
     *
     * @param  App\User  $user
     * @param  App\AMV  $amv
     * @return mixed
     */
    public function view(User $user, AMV $amv)
    {
        return $user->id === $amv->user_id;
    }

    /**
     * Determine whether the user can update the AMV.
     *
     * @param  App\User  $user
     * @param  App\AMV  $amv
     * @return mixed
     */
    public function update(User $user, AMV $amv)
    {
        return $user->id === $amv->user_id;
    }

    /**
     * Determine whether the user can delete the AMV.
     *
     * @param  App\User  $user
     * @param  App\AMV  $amv
     * @return mixed
     */
    public function delete(User $user, AMV $amv)
    {
        return $user->id === $amv->user_id;
    }
}
