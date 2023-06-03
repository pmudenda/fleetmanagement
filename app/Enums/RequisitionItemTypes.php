<?php

namespace App\Enums;

enum RequisitionItemTypes: string
{
    const StockItem = "SI";
    const NonStockItem = "NS";

    const Service = "SE";
}
