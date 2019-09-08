<?php

namespace App\Policies;

use App\Card;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Allow admins to bypass these policy checks.
     *
     * @param User $user
     * @param string $ability
     * @return bool|void
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the card.
     *
     * @param \App\User $user
     * @param \App\Card $card
     * @return mixed
     */
    public function view(User $user, Card $card)
    {
        return $card->tenant_id == $user->tenant_id;
    }

    /**
     * Determine whether the user can create cards.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the card.
     *
     * @param \App\User $user
     * @param \App\Card $card
     * @return mixed
     */
    public function update(User $user, Card $card)
    {
        return $card->domain_id == $user->current_domain_id;
    }

    /**
     * Determine whether the user can delete the card.
     *
     * @param \App\User $user
     * @param \App\Card $card
     * @return mixed
     */
    public function delete(User $user, Card $card)
    {
        return $card->created_by == $user->id;
    }

    /**
     * Determine whether the user can restore the card.
     *
     * @param \App\User $user
     * @param \App\Card $card
     * @return mixed
     */
    public function restore(User $user, Card $card)
    {
        return $card->created_by == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the card.
     *
     * @param \App\User $user
     * @param \App\Card $card
     * @return mixed
     */
    public function forceDelete(User $user, Card $card)
    {
        return $card->created_by == $user->id;
    }
}
