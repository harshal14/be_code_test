<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Organisation;
use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class OrganisationTransformer
 * @package App\Transformers
 */
class OrganisationTransformer extends TransformerAbstract
{
    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function transform(Organisation $organisation): array
    {
        $user = User::find($organisation->owner_user_id);
        return [
            'id'         => (int) $organisation->id,
            'name'       => $organisation->name,
            'owner_user_id'      => $organisation->owner_user_id,
            'owner_details' => (new UserTransformer)->transform($user),
            'trial_end'      => $organisation->trial_end,
            'subscribed'      => $organisation->subscribed,
            'created_at' => (string) $organisation->created_at,
            'updated_at' => (string) $organisation->updated_at,
        ];
    }

    /**
     * @param Organisation $organisation
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Organisation $organisation)
    {
        return $this->item($organisation->user, new UserTransformer());
    }
}
