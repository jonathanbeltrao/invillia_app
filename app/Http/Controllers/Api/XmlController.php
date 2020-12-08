<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadRequest;
use App\Services\FileLogService;
use Illuminate\Support\Facades\Log;

class XmlController extends Controller
{
    protected $service;

    public function __construct(FileLogService $service)
    {
        $this->service = $service;
    }

    public function upload(UploadRequest $request)
    {
        try {
            $this->service->create([
                'customer_xml' => $request->file('customer_xml')->getContent(),
                'order_xml' => $request->file('order_xml')->getContent()
            ]);

            return response(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error('XML Controller Error: ' . $e->getMessage(), $request->allFiles());
            return response(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }
}
