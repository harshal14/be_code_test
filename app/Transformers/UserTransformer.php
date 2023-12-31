<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['organisations'];

    /**
     * @param \App\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => (int) $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
        ];
    }

    /**
     * @param \App\User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeOrganisations(User $user)
    {
        $organisations =  $user->organisations;

        return $this->collection($organisations, new OrganisationTransformer);
    }
}
