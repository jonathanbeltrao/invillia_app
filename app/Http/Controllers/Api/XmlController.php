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

    /**
     * @OA\Post(
     *      path="/api/upload",
     *      operationId="uploadXml",
     *      tags={"Xml"},
     *      summary="Upload XML",
     *      description="Upload given XML",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="customer_xml",
     *                     type="file",
     *                 ),
     *                 @OA\Property(
     *                     property="order_xml",
     *                     type="file",
     *                 ),
     *                 example={"customer_xml": "customer.xml", "order_xml": "order.xml"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="sucess",
     *                         type="boolean",
     *                         description="Status"
     *                     ),
     *                     example={
     *                         "success": true,
     *                     }
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="sucess",
     *                         type="boolean",
     *                         description="Status"
     *                     ),
     *                     example={
     *                         "success": false,
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="sucess",
     *                         type="boolean",
     *                         description="Status"
     *                     ),
     *                     example={
     *                         "success": false,
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     * )
     */

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
