<?php

namespace App\Http\Controllers;

use App\Http\Requests\ETollCardRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class eTollCardController extends Controller
{

    public function report(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modules/tollCardManagement/index');
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
}
