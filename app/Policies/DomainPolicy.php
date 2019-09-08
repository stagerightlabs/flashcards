<?php

namespace App\Policies;

use App\User;
use App\Domain;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    /**
     * Allow admins to bypass these policy checks.
     *
     * @param User $user
     * @param string $ability
     * @return boolean|void
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can create domains.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether this user can update this domain.
     *
     * @param \App\User $user
     * @param \App\Domain $domain
     * @return mixed
     */
    public function update(User $user, Domain $domain)
    {
        return $domain->domain_id == $user->current_domain_id;
    }

    /**
     * Determine whether the user can delete the card.
     *
     * @param \App\User $user
     * @param \App\Domain $domain
     * @return mixed
     */
    public function delete(User $user, Domain $domain)
    {
        return false;
    }
}
