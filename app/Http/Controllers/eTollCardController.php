<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleManagement\ETollCardRequest;
use App\Models\ETollCard;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class eTollCardController extends Controller
{

    public function report(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules/tollCardManagement/index');
    }

    public function uploadTransaction(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules/tollCardManagement/transactions');
    }

    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules/tollCardManagement/create');
    }

    public function list(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules/tollCardManagement/index');
    }

    public function store(ETollCardRequest $request): JsonResponse
    {

        try {
            $user = Auth::user();
            DB::beginTransaction();
            $model = ETollCard::create([
                'batchNumber' => $request->get('batchNumber'),
                'veh_reg' => $request->get('vehicleRegistration'),
                'cardScheme' => $request->get('cardScheme'),
                'cardNumber' => $request->get('cardNumber'),
                'cardStatus' => $request->get('cardStatus'),
                'dateIssued' => Carbon::parse($request->get('dateIssued')),
                'expiryDate' => Carbon::parse($request->get('expiryDate')),
                'cvv' => $request->get('cvv'),
                'contactNumber' => $request->get('contactNumber'),
                'assignedTo' => $request->get('assignedTo'),
                'responseHead' => $request->get('responseHead'),
                'responseHeadId' => $request->get('responseHeadId'),
                'comments' => $request->get('comments'),
                'created_by' => $user->staff_no
            ]);


            if (!empty($request->allFiles())) {
                FileUploadService::uploadFile(
                    $request,
                    'supportingDocument',
                    'eTollCard',
                    $model->id,
                    'eTollCard',
                    'eTollCard',
                    $user
                );
            }

            DB::commit();

            return response()->json([
                'payload' => [],
                'state' => 'success'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'payload' => [],
                'state' => 'failed'
            ]);
        }


    }

    public function saveTransaction(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            foreach ($request->get("items") as $item) {

                ETollCard::create([
                    'batchNumber' => $item['BatchId'],
                    'cardScheme' => $item['Scheme'],
                    'cardNumber' => $item['CardNumber'],
                    'cardStatus' => $item['CardStatus'],
                    'card_value' => $item['CurrentValue'],
                    'assigned_distributor' => $item['AssignedDistributor'],
                    'expiryDate' => Carbon::parse($item['ExpiryDate']),
                    'cvv' => $item['Cvv'],
                    'contactNumber' => $item['Mobile'],
                    'assignedTo' => $item['FirstName'] . ' ' . $item['LastName'],
                    // 'dateIssued' => Carbon::parse($item['DateIssued']),
                    // 'responseHead' => $item['responseHead'],
                    // 'responseHeadId' => $item['responseHeadId'],
                    // 'comments' => $item['comments'],
                    'created_by' => $user->staff_no
                ]);
            }

            DB::commit();

            return response()->json([
                'payload' => [],
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'payload' => [],
                'success' => false
            ]);
        }
    }
}
