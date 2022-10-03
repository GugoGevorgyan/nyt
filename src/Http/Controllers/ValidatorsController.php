<?php

declare(strict_types=1);

namespace Src\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Src\Core\Enums\ConstRedis;
use Src\Services\Company\CompanyServiceContract;

/**
 * Class ValidatorsController
 * @package Src\Http\Controllers
 */
class ValidatorsController extends Controller
{
    /**
     * ValidatorsController constructor.
     * @param  CompanyServiceContract  $companyService
     */
    public function __construct(protected CompanyServiceContract $companyService)
    {
    }

    /**
     * Async unique validator
     *
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function unique(Request $request): Response|ResponseFactory
    {
        $table = $request->query('table');
        $col = $request->query('col');
        $mode = 'edit' === $request->input('mode') ? ','.auth()->id() : '';

        $validator = Validator::make(
            $request->all(),
            [
                $col => "unique:$table,$col$mode"
            ]
        );

        if ($validator->fails()) {
            return response(
                [
                    'valid' => false,
                    'data' => ['message' => $validator->errors()->first($col)]
                ]
            );
        }

        return response(['valid' => true]);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function customUnique(Request $request): Response|Application|ResponseFactory
    {
        $table = $request->table;
        $col = $request->col;
        $id = $request->id ?: 0;
        $idCol = $request->idCol;

        $validator = Validator::make(
            $request->all(),
            [
                $col => "unique:$table,$col,$id,$idCol"
            ]
        );

        if ($validator->fails()) {
            return response(
                [
                    'valid' => false,
                    'data' => ['message' => $validator->errors()->first($col)]
                ]
            );
        }

        return response(['valid' => true]);
    }

    /**
     * Async Exists validator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function exists(Request $request): JsonResponse
    {
        $table = $request->query('table');
        $col = $request->query('col');

        $validator = Validator::make(
            $request->all(),
            [
                $col => "exists:$table,$col"
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'valid' => false,
                    'data' => ['message' => $validator->errors()->first($col)]
                ]
            );
        }

        return response()->json(['valid' => true]);
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function existsClientAcceptCode(Request $request): Response|ResponseFactory
    {
        $accept_code = redis()->hmget(ConstRedis::accept_code('client', $request->phone), ['phone', 'accept_code']);

        if (!$accept_code) {
            return response(['message' => 'Invalid accept code'], 422);
        }

        if ((string)$accept_code[1] !== (string)$request->accept_code) {
            return response(['message' => 'Invalid accept code'], 422);
        }

        return response(['message' => 'ok']);
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function companyHasRegion(Request $request): Response|ResponseFactory
    {
        $result = $this->companyService->hasCompanyIsCoordinate($request->from, $request->company_id);

        if (!$result) {
            return response(['message' => 'Ваша компания не может заплатить поездку в данном регионе', 'status' => 'FAIL', 'code' => 422], 422);
        }

        return response(['message' => 'OK', 'status' => 'ok', 'code' => 200]);
    }
}
