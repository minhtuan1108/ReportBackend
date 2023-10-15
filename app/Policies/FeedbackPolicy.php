<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Feedback;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeedbackPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->tokenCan('manager'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Feedback $feedback): bool
    {
        if ($user->tokenCan('manager') || $user->id == $feedback->report->user->id || $user->id == $feedback->users_id)
            return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Assignment $assignment): bool
    {
        echo("Trong policy: " .$assignment->worker_id == $user->id);
        if (($user->isWorker()) || $user->tokenCan('manager'))
            return true;
        return false;
        // && $assignment->worker_id == $user->id
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Feedback $feedback): bool
    {
        if ($user->tokenCan('manager'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Feedback $feedback): bool
    {
        if ($user->tokenCan('manager'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Feedback $feedback): bool
    {
        if ($user->tokenCan('manager'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Feedback $feedback): bool
    {
        if ($user->tokenCan('manager'))
            return true;
        return false;
    }
}
