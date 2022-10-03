<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\CreateDriverCandidateRequest;
use Src\Http\Requests\SystemWorker\CreateDriverFromCandidateRequest;
use Src\Http\Requests\SystemWorker\DeleteAllCandidates;
use Src\Http\Requests\SystemWorker\DriverCandidateCheckLicenseRequest;
use Src\Http\Requests\SystemWorker\DriverCandidateEditRequest;
use Src\Repositories\DriverGraphic\DriverGraphicContract;
use Src\Repositories\DriverLicenseType\DriverLicenseTypeContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\DriverCandidate\DriverCandidateServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class DriverCandidateController
 * @package Src\Http\Controllers\SystemWorker\PersonalDepartment
 */
class DriverCandidateController extends Controller
{
    /**
     * @var DriverCandidateServiceContract
     */
    protected DriverCandidateServiceContract $baseService;
    /**
     * @var DriverLicenseTypeContract
     */
    protected DriverLicenseTypeContract $driverLicenseTypeContract;
    /**
     * @var WorkerServiceContract
     */
    protected WorkerServiceContract $workerService;
    /**
     * @var DriverGraphicContract
     */
    protected DriverGraphicContract $driverGraphicContract;
    /**
     * @var DriverServiceContract
     */
    protected DriverServiceContract $driverService;
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;
    /**
     * @var DriverCrudContract
     */
    protected DriverCrudContract $driverCrudContract;

    /**
     * DriverCandidateController constructor.
     * @param  DriverCandidateServiceContract  $baseService
     * @param  WorkerServiceContract  $workerService
     * @param  DriverGraphicContract  $driverGraphicContract
     * @param  DriverServiceContract  $driverService
     * @param  RegionServiceContract  $regionService
     * @param  DriverLicenseTypeContract  $driverLicenseTypeContract
     * @param  DriverCrudContract  $driverCrudContract
     */
    public function __construct(
        DriverCandidateServiceContract $baseService,
        WorkerServiceContract $workerService,
        DriverGraphicContract $driverGraphicContract,
        DriverServiceContract $driverService,
        RegionServiceContract $regionService,
        DriverLicenseTypeContract $driverLicenseTypeContract,
        DriverCrudContract $driverCrudContract
    ) {
        $this->baseService = $baseService;
        $this->driverLicenseTypeContract = $driverLicenseTypeContract;
        $this->workerService = $workerService;
        $this->driverGraphicContract = $driverGraphicContract;
        $this->driverService = $driverService;
        $this->regionService = $regionService;
        $this->driverCrudContract = $driverCrudContract;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $types = $this->driverService->getTypes();
        $graphics = $this->driverGraphicContract->getGraphics();

        return view('system-worker.driver-candidates.driver-candidates', compact('types', 'graphics'));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function paginate(Request $request)
    {
        return response(json_encode($this->baseService->candidatesPaginate($request), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK));
    }

    /**
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function create()
    {
        $license_types = $this->driverLicenseTypeContract->findAll();
        $tutors = $this->workerService->getFranchiseTutors();
        $learn_statuses = $this->baseService->getLearnStatuses();

        return \view(
            'system-worker.driver-candidates.create',
            [
                'licenseTypes' => json_encode($license_types, JSON_THROW_ON_ERROR),
                'tutors' => json_encode($tutors, JSON_THROW_ON_ERROR),
                'learnStatuses' => json_encode($learn_statuses, JSON_THROW_ON_ERROR)
            ]
        );
    }

    /**
     * @param  CreateDriverCandidateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function store(CreateDriverCandidateRequest $request)
    {
        return $this->baseService->createCandidate($request->candidate, $request->info)
            ? response(['message' => trans('messages.driver_candidate_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $candidate_id
     * @return Factory|View
     * @throws JsonException
     */
    public function edit($candidate_id)
    {
        $candidate = $this->baseService->getEditedCandidate($candidate_id);

        if (!$candidate) {
            return \view('errors.404');
        }

        $license_types = $this->driverLicenseTypeContract->findAll();
        $tutors = $this->workerService->getFranchiseTutors();
        $learn_statuses = $this->baseService->getLearnStatuses();

        return view(
            'system-worker.driver-candidates.edit',
            [
                'candidate' => json_encode($candidate, JSON_THROW_ON_ERROR),
                'licenseTypes' => json_encode($license_types, JSON_THROW_ON_ERROR),
                'tutors' => json_encode($tutors, JSON_THROW_ON_ERROR),
                'learnStatuses' => json_encode($learn_statuses, JSON_THROW_ON_ERROR)
            ]
        );
    }

    /**
     * @param  DriverCandidateEditRequest  $request
     * @param $candidate_id
     * @return Application|ResponseFactory|Response
     */
    public function update(DriverCandidateEditRequest $request, $candidate_id)
    {
        return $this->baseService->updateCandidate($candidate_id, $request->candidate, $request->info)
            ? response(['message' => trans('messages.driver_candidate_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $candidate_id
     * @return ResponseFactory|Response
     */
    public function delete($candidate_id)
    {
        return $this->baseService->deleteCandidate($candidate_id)
            ? response(['message' => trans('messages.driver_candidate_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  DeleteAllCandidates  $request
     * @return ResponseFactory|Response
     */
    public function deleteMany(DeleteAllCandidates $request)
    {
        return $this->baseService->deleteManyCandidates($request->ids)
            ? response(['message' => trans('messages.driver_candidates_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CreateDriverFromCandidateRequest  $request
     * @return ResponseFactory|Response
     */
    public function candidateCreateDriver(CreateDriverFromCandidateRequest $request)
    {
        return $this->driverCrudContract->candidateCreateDriver($request)
            ? response(['message' => trans('messages.driver_from_candidate_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  DriverCandidateCheckLicenseRequest  $request
     * @return ResponseFactory|Response
     */
    public function checkLicense(DriverCandidateCheckLicenseRequest $request): Response|ResponseFactory
    {
        return response(['candidate' => $this->baseService->checkLicense($request)]);
    }

    /**
     * @param  $info_id
     * @return Application|ResponseFactory|Response|BinaryFileResponse
     */
    public function downloadPassportScan($info_id)
    {
        $path = $this->baseService->getPassportScan((int)$info_id);

        if (!$path) {
            return response(['message' => 'Error scan file not found'], 500);
        }

        if (!\Storage::disk('local')->exists(str_replace('storage', '', $path))) {
            return response(['message' => 'error path'], 500);
        }

        return \Response::download(storage_path(str_replace('storage', 'app', $path)));
    }
}
