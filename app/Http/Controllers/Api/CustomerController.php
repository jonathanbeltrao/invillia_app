<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function all()
    {
        $customers = $this->customerService->all();
        return response($customers,200);
    }

    public function get(Request $request, $id)
    {
        $customer = $this->customerService->get($id);
        return response($customer,200);
    }
}
