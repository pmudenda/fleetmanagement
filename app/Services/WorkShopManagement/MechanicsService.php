<?php

namespace App\Services\WorkShopManagement;

use App\Constants\SystemMessages;
use App\Exceptions\UserDataSyncException;
use App\Exceptions\UserNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Requests\MechanicOnboarding;
use App\Http\Requests\MechanicUpdate;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\Mechanic;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class MechanicsService
{
    const RESULT = ":result";

    public function updateDetails(MechanicUpdate $request): void
    {
        $id = $request->input('mechanicId');
        DB::beginTransaction();
        Mechanic::where('id', '=', $id)
            ->update(
                [
                    'workshop_code' => $request->get('workshop_code'),
                    'extension' => $request->get('phone'),
                    'section_code' => $request->get('workShopSection'),
                    'is_supervisor' => $request->get('workshopSupervisor'),
                ]
            );

        DB::commit();

    }

    public function syncMechanicFullDetails(mixed $userId): void
    {
        $id = $userId;
        Log::debug('Start Syncing Data ' . $userId);
        self::sync($id);
    }

    private function sync($id): void
    {
        try {

            $mechanic = Mechanic::find($id);

            Log::debug('Syncing Mechanic ' . $mechanic->staff_no);

            if (empty($mechanic)) {
                throw new UserNotFoundException("User Not Found");
            }

            $pdo = DB::getPdo();
            $modifiedBy = auth()->user()->staff_no;
            $stmt = $pdo->prepare(
                "begin :result := pkg_employee.fn_sync_mechanic(:p_staff_no, :p_modified_by); end;"
            );

            $userToSync = $mechanic->staff_no;
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_staff_no", $userToSync);
            $stmt->bindParam(":p_modified_by", $modifiedBy);
            $stmt->execute();

            Log::debug($results);

            if (str_starts_with($results, "0")) {
                throw new UserDataSyncException($results);
            }


        } catch (QueryException $exception) {
            Log::debug('Query For User Details Failed');
            Log::error($exception);
        } catch (Exception $e) {
            Log::debug('Error Occurred while Attempting to access PHRIS View');
            Log::error($e);
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function createMechanic(MechanicOnboarding $request): bool
    {
        DB::beginTransaction();

        $employee_phcms = PHCMSEmployee::where(
            'con_per_no',
            '=',
            $request->staff_number
        )->where('con_st_code',
            '=',
            'ACT')->first();

        if (empty($employee_phcms)) {
            throw new UserNotFoundException(str_replace('@user_name',
                $request->staff_number,
                SystemMessages::USER_NOT_VERIFIED
            ));
        }

        $user = auth()->user();

        $data = [
            'email' => strtoupper($request->staff_email),
            'extension' => $request->mobile_no,
            'area_code' => $request->get('business_area'),
            'functional_section' => $request->user_unit,
            'bu_code' => $request->business_unit_code,
            'cc_code' => $request->cost_center_code,
            'directorate' => $request->directorate,
            'user_unit' => $request->user_unit,

            'staff_no' => $employee_phcms->con_per_no,
            'name' => $employee_phcms->name,
            'workshop_code' => $request->workshopCode,
            'section_code' => $request->workShopSection,
            'status' => StatusHelper::active(),
            'created_by' => $user->staff_no,

            'contract_type' => $employee_phcms->contract_type,
            'nrc' => $employee_phcms->nrc,
            'mobile_no' => $employee_phcms->mobile_no,
            'group_type' => $employee_phcms->group_type,
            'job_title' => $employee_phcms->job_title,
            'grade' => $employee_phcms->grade,

            'location' => $employee_phcms->location ?? $employee_phcms->functional_section,
            'pay_point' => $employee_phcms->pay_point,
            'job_code' => $employee_phcms->job_code ?? "--",
            'is_supervisor' => $request->get('workshopSupervisor')
        ];

        Mechanic::firstOrCreate(
            [
                'staff_no' => $request->staff_number,
            ],
            $data
        );

        DB::commit();

        return true;

    }
}
