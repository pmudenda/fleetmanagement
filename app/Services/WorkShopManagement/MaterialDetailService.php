<?php

namespace App\Services\WorkShopManagement;

use Illuminate\Support\Facades\DB;

class MaterialDetailService
{
    public function getReservationDetail($requisitionNumber): mixed
    {
        $results = DB::table("GEN_MATERIAL_HEADERS")
            ->where("GEN_MATERIAL_HEADERS.req_no", $requisitionNumber)
            ->join("GEN_MATERIAL_DETAILS",
                "GEN_MATERIAL_HEADERS.req_no",
                "=", "GEN_MATERIAL_DETAILS.req_no")
            ->leftJoin("CONFIG_STATUSES",
                "GEN_MATERIAL_HEADERS.status",
                "=", "CONFIG_STATUSES.code")
            ->where("CONFIG_STATUSES.MODULE",
                "=", "MAT")
            ->select(
                "GEN_MATERIAL_HEADERS.*",
                "GEN_MATERIAL_DETAILS.*",
                "CONFIG_STATUSES.name as status_name",
                "CONFIG_STATUSES.color_code"
            )->get();

        return $results->first();

    }

}
