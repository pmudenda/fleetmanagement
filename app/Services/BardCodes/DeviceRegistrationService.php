<?php

namespace App\Services;

use App\Models\Device\Device;
use Illuminate\Support\Facades\DB;

class DeviceRegistrationService
{
    public static function generateBarCode(Device $device): string
    {
        $barCodeParams = [
            'text' => $device->serial_number,
            'size' => 50,
            'orientation' => 'horizontal',
            'code_type' => 'code128',
            'print' => true,
            'sizeFactor' => 1,
            'filename' => $device->serial_number,
            'filePath' => 'deviceBarcodes',
            'fileType' => '.jpeg',
        ];

        // <img alt="testing" src="{{asset($barcontent)}}"/>
        $codeService = new BarcodeGenerationService();
        $barCodePath = $codeService->renderBarcode(
            $barCodeParams["text"],
            $barCodeParams['size'],
            $barCodeParams['orientation'],
            $barCodeParams['code_type'], // code_type : code128,code39,code128b,code128a,,
            $barCodeParams['print'],
            $barCodeParams['sizeFactor'],
            $barCodeParams['filename'] . $barCodeParams['fileType'],
            $barCodeParams['filePath'],
            $barCodeParams['fileType'],
        )->filename($barCodeParams['filename'] . $barCodeParams['fileType']);

        DB::table('devices')
            ->where('id', '=', $device->id)
            ->update(['barcode' => $barCodePath]);

        return $barCodePath;
    }
}
