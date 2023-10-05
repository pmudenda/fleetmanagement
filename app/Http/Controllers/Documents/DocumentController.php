<?php

namespace App\Http\Controllers\Documents;

use App\Constants\QueryComparisonOperator;
use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Reference\DocumentFollowup;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function documentFollowup(Request $request): View
    {
        Log::debug("Making Document Followup");
        try {
            $query = DocumentFollowup::query();
            if ($request->has('documentType')
                && $request->filled('documentType')) {
                $query->where(function ($query) use ($request) {
                    $query->where("type_document",
                        QueryComparisonOperator::EQUALS,
                        strtoupper(trim($request->get('documentType'))));
                });
            }
            if ($request->has('documentNumber') && $request->filled('documentNumber')) {
                $query->where(function ($query) use ($request) {
                    $query->where("document_no",
                        QueryComparisonOperator::EQUALS,
                        strtoupper(trim($request->get('documentNumber'))));
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
                    $query->whereDate('date_act',
                        QueryComparisonOperator::GREATER_THAN_EQUAL,
                        $startDate);
                });

            } elseif ($request->has('periodTo') && $request->filled('periodTo')) {
                $query->where(function ($query) use ($request) {
                    $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                    $query->whereDate('date_act',
                        QueryComparisonOperator::LESS_THAN_EQUAL,
                        $endDate);
                });

            }

            $data = $query->paginate(50);
            Log::debug("Running Query");

            return view("documents/documentFollowUp", compact('data'));

        } catch (\Exception $e) {
            Log::error($e);
            return view("documents/documentFollowUp", compact('data'));
        }

    }

    public function documentAuditTrail(Request $request): JsonResponse
    {
        $query = DocumentFollowup::query();

        if ($request->has('documentType') && !empty($request->has('documentType'))) {
            $query->where(function ($query) use ($request) {
                $documentType = strtoupper(trim($request->get('documentType')));
                $query->where("type_document",
                    QueryComparisonOperator::EQUALS,
                    $documentType);
            });
        }

        if ($request->has('documentNumber') && !empty($request->has('documentNumber'))) {
            $query->where(function ($query) use ($request) {
                $documentNo = strtoupper(trim(
                    $request->get('documentNumber')));
                $query->where("document_no",
                    QueryComparisonOperator::EQUALS,
                    $documentNo);
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
                $query->where('date_act',
                    QueryComparisonOperator::GREATER_THAN_EQUAL,
                    $startDate)->where('date_act',
                    QueryComparisonOperator::LESS_THAN_EQUAL,
                    $endDate);
            });
        } elseif ($request->has('periodFrom') && $request->filled('periodFrom')) {

            $query->where(function ($query) use ($request) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->get('periodFrom'));
                $query->where('date_act',
                    QueryComparisonOperator::GREATER_THAN_EQUAL,
                    $startDate);
            });

        } elseif ($request->has('periodTo') && $request->filled('periodTo')) {

            $query->where(function ($query) use ($request) {
                $endDate = Carbon::createFromFormat('Y-m-d', $request->get('periodTo'));
                $query->where('date_act',
                    QueryComparisonOperator::LESS_THAN_EQUAL,
                    $endDate);
            });

        }

        return response()->json(
            FleetMasterJsonResponse::response(
                ResponseState::SUCCESS->value,
                true,
                null,
                $query->get()
            )
        );

    }

}
