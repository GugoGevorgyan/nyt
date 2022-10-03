<?php

declare(strict_types=1);

namespace Src\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Requests\Versioning\ChangeVersioningRequest;
use Src\Http\Requests\Versioning\GetVersioningRequest;
use Src\Http\Resources\App\GetVersioningResource;
use Src\Repositories\Versioning\VersionContract;

/**
 *
 */
class VersioningController extends Controller
{
    /**
     * @param  VersionContract  $versionContract
     */
    public function __construct(protected VersionContract $versionContract)
    {
    }

    /**
     * @param  GetVersioningRequest  $request
     * @return Application|ResponseFactory|Response|GetVersioningResource
     */
    public function getVersion(GetVersioningRequest $request): Response|GetVersioningResource|Application|ResponseFactory
    {
        $version = $this->versionContract
            ->where('auth_key', '=', $request->key)
            ->findFirst(['version_id', 'version', 'state', 'updated_at']);

        if (!$version) {
            return response(['message' => 'Error not found version for this request'], 500);
        }

        return (new GetVersioningResource($version));
    }

    /**
     * @param  ChangeVersioningRequest  $request
     * @return GetVersioningResource|string
     */
    public function editVersion(ChangeVersioningRequest $request): GetVersioningResource|string
    {
        try {
            $this->versionContract
                ->where('auth_key', '=', $request->key)
                ->updateSet(['version' => $request->version, 'state' => $request->state]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $result = $this->versionContract
            ->where('auth_key', '=', $request->key)
            ->findFirst(['version_id', 'version', 'updated_at', 'state']);

        return (new GetVersioningResource($result));
    }
}
