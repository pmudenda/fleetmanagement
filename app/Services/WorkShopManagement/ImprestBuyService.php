<?php

namespace App\Services\WorkShopManagement;

use App\Constants\SystemMessages;
use App\Constants\WorkflowModules;
use App\Events\PettyCashRaised;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\PettyCashItems;
use App\Models\WorkShopManagement\ImprestBuyDetail;
use App\Models\WorkShopManagement\ImprestBuyHeader;
use App\Services\Workflow\DocumentNumberGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ImprestBuyService
{
    const RESULT = ':result';

    /**
     * @param string $imprestReferenceNumber
     * @param string $staff_no
     */
    public function postToPettyCashSystem(string $imprestReferenceNumber, string $staff_no): void
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare(
            "begin :result := pkg_imprest_buy.fn_create_imprest_req(:p_imprest_reference,
            :p_current_user); end;"
        );

        $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
        $stmt->bindParam(":p_current_user", $staff_no);
        $stmt->bindParam(":p_imprest_reference", $imprestReferenceNumber);
        $stmt->execute();

        Log::info("Posting Data");
        Log::info($results);
        Log::info("Logging Response From Petty Cash System");
    }

    public function save(PettyCashItems $request): void
    {
        $user = auth()->user();
        DB::beginTransaction();

        $imprestReferenceNumber = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::IMPREST_BUY
        );

        ImprestBuyHeader::firstOrCreate(
            [
                'work_order_number' => $request->get('imprestProjectNumber'),
            ],
            [
                'imprest_reference' => $imprestReferenceNumber,
                'cost_center' => $user->cc_code,
                'business_unit_code' => $user->bc_code,
                'work_order_number' => $request->get('imprestProjectNumber'),
                'total_payment' => floatval($request->get('totalPayment')),
                'zqms_ref_no' => $request->get('imprestZQMSReference'),
                'status' => StatusHelper::new(),
                'name' => $user->name,
                'staff_no' => $user->staff_no,
                'claim_date' => Carbon::now(),
                'created_by' => $user->staff_no,
            ]);

        foreach ($request->get('items') as $item) {
            ImprestBuyDetail::create(
                [
                    'header_reference' => $imprestReferenceNumber,
                    'vehicle_registration' => $item['imprestVehicleRegistration'],
                    'material_code' => $item['imprestArticleCode'],
                    'description' => $item['imprestArticleDescription'],
                    'specification' => $item['imprestArticleDescription'],
                    'quantity' => (int)$item['imprestItemQty'],
                    'unit_of_measure' => $item['imprestItemUnitOfMeasure'],
                    'unit_price' => floatval($item['imprestItemUnitPrice']),
                    'total_price' => floatval($item['imprestItemTotalPrice']),
                    'created_by' => $user->staff_no
                ]
            );
        }

        DB::commit();

        PettyCashRaised::dispatch(
            $imprestReferenceNumber, auth()->user()->staff_no
        );
    }

    /**
     * @throws DataNotFoundException
     */
    public function voidPettyCash(Request $request): void
    {
        $entry = ImprestBuyDetail::where("id", "=", $request->record_id)
            ->first();

        if (empty($entry)) {
            throw new DataNotFoundException(SystemMessages::RECORD_NOT_FOUND);
        }

        $header = ImprestBuyHeader::where('imprest_reference', '=', $entry->header_reference)
            ->first();

        DB::beginTransaction();

        $header->deleted_at = Carbon::now();
        $entry->deleted_at = Carbon::now();
        $header->save();
        $entry->save();


        DB::commit();
    }
}
