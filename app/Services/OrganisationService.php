<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\OrganisationCreationMail;
use App\Organisation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        $newDateTime = Carbon::now()->addDays(30);
        $trial_end_date = $newDateTime;
        $organisation = new Organisation();
        $organisation->name = $attributes['name'];
        $organisation->owner_user_id = $attributes['owner_user_id'];
        $organisation->trial_end = $trial_end_date;
        $organisation->subscribed = isset($attributes['subscribed']) ?: 0;
        $organisation->save();
        $details = [
            'title' => 'New Organisation details',
            'organisation_details' => $organisation
        ];
        Mail::to('harshal.badge@gmail.com')->send(new OrganisationCreationMail($details));
        return $organisation;
    }

    public function getOrganisations(array $attributes)
    {
        $filter = isset($_GET['filter']) ?: false;
        $query_Param = '';
        if ($filter == true) {
            $query_Param = $_GET['filter'];
        }
        $where_condition = null;
        if ($query_Param == 'trial') {
            $where_condition = [
                'subscribed' => 0,
            ];
        }
        if ($query_Param == 'subbed') {
            $where_condition = [
                'subscribed' => 1,
            ];
        }
        $Organisations = Organisation::where($where_condition)->get();
        return $Organisations;
    }
}
