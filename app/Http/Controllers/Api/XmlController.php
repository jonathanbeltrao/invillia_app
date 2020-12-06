<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadRequest;
use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function __construct()
    {
    }

    public function upload(UploadRequest $request)
    {
        dd($request);
    }
}
