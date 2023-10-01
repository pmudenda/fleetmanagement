<?php

namespace App\Enums;

enum RequisitionItemTypes: string
{
    const STOCK_ITEM = "SI";
    const NON_STOCK_ITEM = "NS";

    const SERVICE = "SE";

    const STOCK_ITEM_CODE = "01";

    const NON_STOCK_ITEM_CODE = "02";

    const SERVICE_ITEM_CODE = "03";
}
