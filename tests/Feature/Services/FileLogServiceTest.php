<?php

namespace Tests\Feature\Services;

use App\Models\FileLog;
use App\Services\FileLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileLogServiceTest extends TestCase
{
    use RefreshDatabase;

    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FileLogService::class);
    }

    public function testUpload()
    {
        Storage::fake('local');

        //Mock file
        $filePath = __DIR__.'/../mocks/people.xml';
        $file_content = file_get_contents($filePath);

        $fileName = $this->service->upload($file_content);

        Storage::disk('local')->assertExists('xmls/' . $fileName);
    }

    public function testCreate() {
        Storage::fake('local');

        //Mock file
        $customerFilePath = __DIR__.'/../mocks/people.xml';
        $orderFilePath = __DIR__.'/../mocks/people.xml';

        $customerFileContent = file_get_contents($customerFilePath);
        $orderFileContent = file_get_contents($orderFilePath);

         $this->service->create([
            'customer_xml' => $customerFileContent,
            'order_xml' => $orderFileContent
         ]);

         $files = FileLog::all();

         $this->assertCount(1, $files);
    }
}
