<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\OrganisationCreationMail;
use App\Organisation;
use App\Services\OrganisationService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationService $service): JsonResponse
    {
        if (!Auth::guard('api')->check()) {
            return response()->json(['errors' => 'Unauthorised Access'], 401);
        }

        $validator = Validator::make($this->request->all(), array(
            'name' => 'required|max:255|unique:organisations',
            'owner_user_id' => 'required|numeric|exists:users,id'
        ));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        /** @var Organisation $organisation */
        $organisation = $service->createOrganisation($this->request->all());

        return $this
            ->transformItem('organisation', $organisation, ['user'])
            ->respond();
    }

    public function listAll(OrganisationService $service): JsonResponse
    {
        $organisation = $service->getOrganisations($this->request->all());
        return $this
            ->transformCollection('organisation', $organisation, ['user'])
            ->respond();
    }
}
