<?php

namespace App\Services\FileUploads;

use App\Models\Attachment;
use App\Models\general\File;
use Illuminate\Http\Request;

class FileUploadService
{
    /**
     * Upload files to file system and save details to datastore
     * @param Request $request HttpRequest Object
     * @param string $fileKey property that indicates field having attachments
     * @param string $folder the location on the file system where files will be uploaded
     * @param string $code for the request that will be used to associate uploaded files to the form
     * @param string $formType identifies the request type e.g subsistence, hotel-accommodation etc.
     * @param string $fileType identifies the nature of the file being uploaded e.g. invoice, receipt etc.
     * @return void
     */
    public static function uploadFile(
        Request $request,
        string  $fileKey,
        string  $folder,
        string  $code,
        string  $formType,
        string  $fileType,
        $user
    ): array
    {
        if (empty($request->file())) {
            return [];
        }

        $files = $request->file($fileKey);

        if (is_array($files)) {
            $uploadedFiles = [];
            foreach ($files as $file) {
                $uploadedFiles[] = self::upload(
                    $file,
                    $folder,
                    $code,
                    $formType,
                    $fileType,
                    $user
                );
            }
            return $uploadedFiles;
        }

        $file = $files;

        $uploadedFile = self::upload(
            $file,
            $folder,
            $code,
            $formType,
            $fileType,
            $user
        );
        return array($uploadedFile);
    }

    /**
     * @param $file
     * @param string $folder
     * @param string $code
     * @param string $module
     * @param string $fileType
     * @return void
     */
    public static function upload(
               $file,
        string $folder,
        string $code,
        string $module,
        string $fileType,
               $user
    ): File
    {
        // Get just filename
        $filename = pathinfo(preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName()), PATHINFO_FILENAME);
        //get size
        $size = number_format($file->getSize() * 0.000001, 2);
        // Get just ext
        $extension = $file->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
        // Upload File
        $path = $file->storeAs('public/' .$folder, $fileNameToStore);

        //upload the receipt
        return File::create(
            [
                'module' => $module,
                'reference_number' => $code,
                'name' => $fileNameToStore,
                'originalDocumentName' => $file->getClientOriginalName(),
                'extension' => $extension,
                'path' => trim(str_replace('public', '', $path)),
                'file_type' => $fileType,
                'file_size' => $size,
                'created_by' => $user->id
            ]
        );
    }
}
