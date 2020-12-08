<?php

namespace App\Jobs;

use App\Models\FileLog;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Orchestra\Parser\Xml\Facade as XmlParser;
use phpDocumentor\Reflection\Types\Integer;

class ConsumeXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $log_id;
    protected $customerService;
    protected $orderService;

    public function __construct(int $log_id)
    {
        $this->log_id = $log_id;
        $this->customerService = app(CustomerService::class);
        $this->orderService = app(OrderService::class);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = FileLog::find($this->log_id);

        $customerFile = Storage::get("xmls/" . $log->customer_xml);
        $customerXml = json_encode(simplexml_load_string($customerFile));
        $arrayCustomer = json_decode($customerXml);

        $importCustomer = $this->customerService->create($arrayCustomer);

        if($importCustomer) {
            $orderFile = Storage::get("xmls/" . $log->order_xml);
            $orderXml = json_encode(simplexml_load_string($orderFile));
            $arrayOrders = json_decode($orderXml);

            dd($this->orderService->create($arrayOrders));
        }


    }
}
