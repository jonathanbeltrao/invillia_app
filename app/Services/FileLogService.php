<?php


namespace App\Services;


use App\Jobs\ConsumeXml;
use App\Models\FileLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;
use SebastianBergmann\CodeCoverage\XmlException;
use SebastianBergmann\Environment\Console;

class FileLogService
{

    public function create($files)
    {
        $customerFilename = $this->upload($files['customer_xml']);
        $orderFilename = $this->upload($files['order_xml']);

        if(!is_null($customerFilename) && !is_null($orderFilename)) {
            $log_id = FileLog::create([
                'customer_xml' => $customerFilename,
                'order_xml' => $orderFilename
            ]);

            dispatch(new ConsumeXml($log_id->id));
        }
    }

    public function upload($file_content): ?string
    {
        $root_folder = 'xmls';
        try {
            $str = \Illuminate\Support\Str::random(10);
            Storage::disk('local')->put("{$root_folder}/{$str}.xml", $file_content);

            Log::info("File Uploaded: {$str}.xml");
            return $str . '.xml';
        } catch (XmlException $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }
}
