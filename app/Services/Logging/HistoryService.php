<?php

namespace App\Services\Logging;

use App\Constants\EventTypes;
use App\Helpers\StringUtils;
use App\Models\AuditTrail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HistoryService
{
    /**
     * Write history after update
     * @param array $dataBefore
     * @param array $dataAfter
     * @param string $taskNumber
     * @param string $eventSubject
     * @return void
     */
    public static function update(array $dataBefore, array $dataAfter, string $taskNumber, string $eventSubject): void
    {
        $ignoredColumnChanges = ['updated_at'];
        $user = Auth::user();

        foreach ($dataBefore as $propertyName => $valueBefore) {
            // if column is in columns to be ignored
            if (in_array($propertyName, $ignoredColumnChanges)) {
                continue;
            }

            if ($valueBefore != $dataAfter[$propertyName]) {

                $data = [
                    'event_date' => Carbon::now(),
                    'referenceNumber' => $taskNumber,
                    'event' => EventTypes::updated(),
                    'subject' => $eventSubject,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'field_action' => StringUtils::camelCaseToWords($propertyName),
                    'old_value' => $valueBefore,
                    'new_value' => $dataAfter[$propertyName]
                ];

                var_dump($data);

                //AuditTrail::create($data);
            }
        }
    }

    public static function record($record, string $recordReferenceNumber, $eventSubject): void
    {
        $user = Auth::user();
        $writeData = $record->toArray();
        $ignoredColumnChanges = ['updated_at', 'created_at'];

        foreach ($writeData as $propertyName => $value) {
            // if column is in columns to be ignored
            if (in_array($propertyName, $ignoredColumnChanges)) {
                continue;
            }

            $data = [
                'event_date' => Carbon::now(),
                'referenceNumber' => $recordReferenceNumber,
                'event' => EventTypes::updated(),
                'subject' => $eventSubject,
                'user_id' => $user->id,
                'name' => $user->name,
                'field_action' => StringUtils::camelCaseToWords($propertyName),
                'new_value' => $value
            ];

            AuditTrail::create($data);

        }
    }
}
