<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Reference\DocumentFollowup;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function documentFollowup(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        Log::info("Making Document Followup");
        try {
            $query = DocumentFollowup::query();
            if ($request->has('documentType') && $request->filled('documentType')) {
                $query->where(function ($query) use ($request) {
                    $query->where("type_document", "=", strtoupper(trim($request->get('documentType'))));
                });
            }
            if ($request->has('documentNumber') && $request->filled('documentNumber')) {
                $query->where(function ($query) use ($request) {
                    $query->where("document_no", "=", strtoupper(trim($request->get('documentNumber'))));
                });
            }
            if ($request->has('periodFrom')
                && $request->has('periodTo')
                && $request->filled('periodFrom')
                && $request->filled('periodTo')
            ) {
                $query->where(function ($query) use ($request) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $request->get('periodFrom'));
                    $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                    $query->whereBetween('date_act', [$startDate, $endDate]);
                });
            } elseif ($request->has('periodFrom') && $request->filled('periodFrom')) {

                $query->where(function ($query) use ($request) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $request->get('periodFrom'));
                    $query->whereDate('date_act', '>=', $startDate);
                });

            } elseif ($request->has('periodTo') && $request->filled('periodTo')) {
                $query->where(function ($query) use ($request) {
                    $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                    $query->whereDate('date_act', '<=', $endDate);
                });

            }

            $data = $query
                ->leftJoin('config_general_tables', "")
                ->paginate(50);
            Log::info("Running Query");
            return view("documents/documentFollowUp")->with(compact('data'));

        } catch (\Exception $e) {
            Log::error($e);
            return view("documents/documentFollowUp")->with(compact('data'));
        }

    }

    public function documentAuditTrail(Request $request): JsonResponse
    {
        $query = DocumentFollowup::query();

        if ($request->has('documentType') && !empty($request->has('documentType'))) {
            $query->where(function ($query) use ($request) {
                $documentType = strtoupper(trim($request->get('documentType')));
                $query->where("type_document", "=", $documentType);
            });
        }

        if ($request->has('documentNumber') && !empty($request->has('documentNumber'))) {
            $query->where(function ($query) use ($request) {
                $documentNo = strtoupper(trim($request->get('documentNumber')));
                $query->where("document_no", "=", $documentNo);
            });
        }

        if ($request->has('periodFrom')
            && $request->has('periodTo')
            && $request->filled('periodFrom')
            && $request->filled('periodTo')
        ) {
            $query->where(function ($query) use ($request) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->get('periodFrom'));
                $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                $query->where('date_act', '>=', $startDate)->where('date_act', '<=', $endDate);
            });
        } elseif ($request->has('periodFrom') && $request->filled('periodFrom')) {

            $query->where(function ($query) use ($request) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->get('periodFrom'));
                $query->where('date_act', '>=', $startDate);
            });

        } elseif ($request->has('periodTo') && $request->filled('periodTo')) {

            $query->where(function ($query) use ($request) {
                $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                $query->where('date_act', '<=', $endDate);
            });

        }

        return response()->json([
            'state' => 'success',
            'payload' => $query->get()
        ]);

    }

}
