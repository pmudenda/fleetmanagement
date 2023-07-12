<?php

namespace App\Constants;

class TransactionType
{
    const FuelRequisition = '01';
    const NonFuelStoresRequisition = '01';

    const STORES_REQUISITIONS = '01';
    const STORES_ISSUES = '02';
    const STORES_RETURN = '03';
    const SERVICE_PURCHASE_REQUISITIONS = '04';
    const SERVICE_JOB_RECEIPTS = '05';
    const SERVICE_JOB_RECEIPTS_REPEATED = '06';
}
