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

    /**
     * @OA\Get(
     *      path="/api/customers",
     *      operationId="getCustomers",
     *      tags={"Customer"},
     *      summary="Get all Customers and its info",
     *      description="Get all Customers and its info",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="string",
     *                         description="Customer ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Customer Name"
     *                     ),
     *                     @OA\Property(
     *                         property="orders",
     *                         type="string",
     *                         description="Customer Orders"
     *                     ),
     *                     @OA\Property(
     *                         property="phones",
     *                         type="string",
     *                         description="Customer Phones"
     *                     ),
     *                     example={
    {
        "id": 1,
        "customer_id": 1,
        "address": {
            "id": 10,
            "order_id": 1,
            "name": "Name 1",
            "address": "Address 1",
            "city": "City 1",
            "country": "Country 1"
        },
        "items": {
     *     {
                "id": 9,
                "order_id": 1,
                "title": "Title 1",
                "note": "Note 1",
                "quantity": 745,
                "price": 123.45
     *     }
        },
        "phones":{
            {
                "id": 76,
                "customer_id": 1,
                "number": "2345678",
                "created_at": "2020-12-08T05:45:15.000000Z",
                "updated_at": "2020-12-08T05:45:15.000000Z",
                "deleted_at": null
            },
            {
                "id": 77,
                "customer_id": 1,
                "number": "1234567",
                "created_at": "2020-12-08T05:45:15.000000Z",
                "updated_at": "2020-12-08T05:45:15.000000Z",
                "deleted_at": null
            },
        }
*     },
*     },
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function all()
    {
        $customers = $this->customerService->all();
        return response($customers,200);
    }

    /**
     * @OA\Get(
     *      path="/api/customer/{id}",
     *      operationId="getCustomer",
     *      tags={"Customer"},
     *      summary="Get a Customers by its ID",
     *      description="Get a Customer and its info",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer ID",
     *         required=true,
     *         @OA\Schema(
     *             type="int"
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
     *                         property="id",
     *                         type="string",
     *                         description="Customer ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Customer Name"
     *                     ),
     *                     @OA\Property(
     *                         property="orders",
     *                         type="string",
     *                         description="Customer Orders"
     *                     ),
     *                     @OA\Property(
     *                         property="phones",
     *                         type="string",
     *                         description="Customer Phones"
     *                     ),
     *                     example={
    "id": 1,
    "customer_id": 1,
    "address": {
    "id": 10,
    "order_id": 1,
    "name": "Name 1",
    "address": "Address 1",
    "city": "City 1",
    "country": "Country 1"
    },
    "items": {
     *     {
    "id": 9,
    "order_id": 1,
    "title": "Title 1",
    "note": "Note 1",
    "quantity": 745,
    "price": 123.45
     *     }
    },
    "phones":{
    {
    "id": 76,
    "customer_id": 1,
    "number": "2345678",
    "created_at": "2020-12-08T05:45:15.000000Z",
    "updated_at": "2020-12-08T05:45:15.000000Z",
    "deleted_at": null
    },
    {
    "id": 77,
    "customer_id": 1,
    "number": "1234567",
    "created_at": "2020-12-08T05:45:15.000000Z",
    "updated_at": "2020-12-08T05:45:15.000000Z",
    "deleted_at": null
    }
     *     },
     *     },
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function get(Request $request, $id)
    {
        $customer = $this->customerService->get($id);
        return response($customer,200);
    }
}
