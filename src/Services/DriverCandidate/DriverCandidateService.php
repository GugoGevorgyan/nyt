<?php

declare(strict_types=1);


namespace Src\Services\DriverCandidate;


use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ServiceEntity\BaseService;
use Src\Repositories\DriverCandidate\DriverCandidateContract;
use Src\Repositories\DriverInfo\DriverInfoContract;
use Src\Repositories\DriverInfoLicenseType\DriverInfoLicenseTypeContract;
use Src\Repositories\DriverLicenseType\DriverLicenseTypeContract;
use Src\Repositories\Image\ImageContract;
use Src\Repositories\LearnStatus\LearnStatusContract;

/**
 * Class DriverCandidateService
 * @package Src\Services\DriverCandidate
 */
final class DriverCandidateService extends BaseService implements DriverCandidateServiceContract
{
    /**
     * DriverCandidateService constructor.
     * @param  DriverCandidateContract  $baseContract
     * @param  DriverInfoContract  $driverInfoContract
     * @param  LearnStatusContract  $learnStatusContract
     * @param  ImageContract  $imageContract
     * @param  DriverLicenseTypeContract  $driverLicenseTypeContract
     * @param  DriverInfoLicenseTypeContract  $driverInfoLicenseTypeContract
     */
    public function __construct(
        protected DriverCandidateContract $baseContract,
        protected DriverInfoContract $driverInfoContract,
        protected LearnStatusContract $learnStatusContract,
        protected ImageContract $imageContract,
        protected DriverLicenseTypeContract $driverLicenseTypeContract,
        protected DriverInfoLicenseTypeContract $driverInfoLicenseTypeContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCandidatesForCreateTutor($offset, $limit): ?Collection
    {
        return $this->baseContract->offset((int)$offset)->limit($limit)->with('image')->findAll() ?: null;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function createCandidate($candidate_data, $info_data): ?int
    {
        return $this->baseContract->beginTransaction(function () use ($candidate_data, $info_data) {
            $this->baseContract->forgetCache();

            $info_data = $this->uploadInfoFiles($info_data);
            $info_data['franchise_id'] = FRANCHISE_ID;

            $info = $this->driverInfoContract->create($info_data);

            if (!$info) {
                return null;
            }

            foreach ($info_data['license_type_ids'] as $types) {
                $this->driverInfoLicenseTypeContract->create(['driver_info_id' => $info->driver_info_id, 'license_type_id' => $types]);
            }

            $candidate_data['franchise_id'] = FRANCHISE_ID;
            $candidate_data['driver_info_id'] = $info['driver_info_id'];

            $driver_candidate = $this->baseContract->create($candidate_data);

            return $driver_candidate->driver_candidate_id ?? null;
        });
    }

    /**
     * @param  array  $info
     * @param  object|null  $oldInfo
     * @return array
     */
    protected function uploadInfoFiles(array $info, object $oldInfo = null): array
    {
        $path = storage_path('public'.DS.'drivers'.DS.'info'.DS);
        $web_path = '/storage/drivers/info/';

        $files = [];
        if (!empty($info['passport_scan']) && null !== $info['passport_scan'][0] && !\is_string($info['passport_scan'])) {
            foreach ($info['passport_scan'] as $passport_scan) {
                $files[] = $this->fileUpload($passport_scan, $path, [700, 500]);
            }

            $pdf_name = Str::random().'.pdf';
            $pdf = PDF::loadView('system-worker.driver-contract-files.image-insert', ['images' => $files, 'path' => $path]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_paper("A4");

            $pdf->save(storage_path('drivers'.DS.'info'.DS.$pdf_name));

            foreach ($files as $file) {
                $this->deleteOldFile('drivers'.DS.'info'.DS.$file);
            }

            $info['passport_scan'] = $web_path.$pdf_name;
            unset($info['passport_scan']);
        }

        if (isset($info['license_scan_file'])) {
            $info['license_scan'] = $web_path.$this->fileUpload($info['license_scan_file'], $path);
            if ($oldInfo) {
                $this->deleteOldFile($oldInfo['license_scan']);
            }
        }

        if (isset($info['license_qr_code_file'])) {
            $info['license_qr_code'] = $web_path.$this->fileUpload($info['license_qr_code_file'], $path);
            if ($oldInfo) {
                $this->deleteOldFile($oldInfo['license_qr_code']);
            }
        }

        if (isset($info['photo_file'])) {
            $info['photo'] = $web_path.$this->fileUpload($info['photo_file'], $path);
            if ($oldInfo) {
                $this->deleteOldFile($oldInfo['photo']);
            }
        }

        return $info;
    }

    /**
     * @param $candidate_id
     * @return mixed
     */
    public function getEditedCandidate($candidate_id)
    {
        return $this->baseContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with([
                'info.license_types' => fn($query) => $query->select(['name', 'type', 'driver_license_type_id']),
                'info' => fn($query) => $query->select([
                    'driver_info_id',
                    'name',
                    'surname',
                    'patronymic',
                    'email',
                    'license_qr_code',
                    'license_code',
                    'license_scan',
                    'license_date',
                    'license_expiry',
                    'passport_serial',
                    'passport_scan',
                    'photo',
                    'experience',
                    'birthday',
                    'passport_number',
                    'passport_issued_by',
                    'passport_when_issued',
                    'citizen',
                    'address',
                    'deposit',
                    'id_kis_art'
                ])
            ])
            ->find($candidate_id, [
                'driver_candidate_id',
                'tutor_id',
                'driver_info_id',
                'franchise_id',
                'phone',
                'learn_status_id',
                'learn_start',
                'learn_end',
            ]);
    }

    /**
     * @param $candidate_id
     * @param $candidate
     * @param $info
     * @return bool|mixed|null
     * @throws Exception
     */
    public function updateCandidate($candidate_id, $candidate, $info): bool
    {
        $findCandidate = $this->baseContract->withTrashed()->where('franchise_id', '=', FRANCHISE_ID)->find($candidate_id);

        if (!$findCandidate) {
            return false;
        }

        $findCandidate->restore();

        return $this->baseContract->beginTransaction(function () use ($candidate_id, $candidate, $info) {
            $this->baseContract->forgetCache();

            if (!$this->baseContract->update($candidate_id, $candidate)) {
                return false;
            }

            if (!$this->updateDriverInfo($info)) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param $info_data
     * @return bool
     */
    protected function updateDriverInfo($info_data): bool
    {
        try {
            $info = $this->driverInfoContract->find($info_data['driver_info_id']);
            $info_data = $this->uploadInfoFiles($info_data, $info);

            if (!$this->driverInfoLicenseTypeContract->where('driver_info_id', '=', $info->driver_info_id)->deletes()) {
                return false;
            }

            foreach ($info_data['license_type_ids'] as $types) {
                $this->driverInfoLicenseTypeContract->create(['driver_info_id' => $info->driver_info_id, 'license_type_id' => $types]);
            }

            if (!$this->driverInfoContract->where('driver_info_id', '=', $info_data['driver_info_id'])->updateSet($info_data)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $candidate_id
     * @return mixed
     */
    public function deleteCandidate($candidate_id)
    {
        return $this->baseContract->delete($candidate_id);
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function candidatesPaginate($request): LengthAwarePaginator // @todo fix query
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && null != $request->search ? $request->search : null;

        return $this->baseContract
            ->doesntHave('driver')
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with([
                'info.license_types' => fn($query) => $query->select(['driver_license_type_id', 'name', 'type']),
                'tutor' => fn($query) => $query->select(['system_worker_id', 'name']),
                'driver' => fn($q) => $q->withCount('contracts')->withTrashed()
            ])
            ->when($search, fn($q) => $q->where(fn($q) => $q
                ->whereHas('info', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$search.'%'))))
            ->orderBy('driver_candidate_id', 'desc')
            ->paginate($per_page, [
                'driver_candidate_id',
                'tutor_id',
                'driver_info_id',
                'franchise_id',
                'phone',
                'learn_status_id',
                'learn_start',
                'learn_end',
            ], 'page', $page);
    }

    /**
     * @param $candidate_ids
     * @return mixed
     */
    public function deleteManyCandidates($candidate_ids)
    {
        return $this->baseContract->deletesBy('driver_candidate_id', $candidate_ids);
    }

    /**
     * @param $request
     * @return object|null
     */
    public function checkLicense($request): ?object
    {
        return $this->baseContract
            ->withTrashed() // @todo fix withTrashed on repo
            ->whereHas('info', fn($q) => $q->where('license_code', '=', $request->license))
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with([
                'info.license_types',
                'driver' => fn($q) => $q->with([
                    'type',
                    'car' => fn($q) => $q->with('park', 'classes'),
                    'contracts' => fn($q) => $q->with(['type', 'graphic', 'car' => fn($q) => $q->with('park', 'car_class')])
                ])
            ])
            ->findFirst();
    }

    /**
     * @inheritDoc
     */
    public function getPassportScan(int $info_id): string
    {
        $scan = $this->driverInfoContract->find($info_id, ['driver_info_id', 'passport_scan']);

        return $scan->passport_scan ?? '';
    }

    /**
     * @return Collection|mixed
     */
    public function getLearnStatuses()
    {
        return $this->learnStatusContract->findAll();
    }
}
