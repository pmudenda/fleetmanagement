<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\reference\DocumentFollowup;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function documentFollowup(Request $request): JsonResponse
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
