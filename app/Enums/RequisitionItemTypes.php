<?php

namespace App\Enums;

enum RequisitionItemTypes: string
{
    const StockItem = "SI";
    const NonStockItem = "NS";

    const Service = "SE";

    const StockItemCode = "01";
    const NonStockItemCode = "02";
    const ServiceItemCode = "03";
}
