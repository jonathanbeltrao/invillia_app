<?php


namespace App\Services;


use App\Models\Customer;
use App\Models\CustomerPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            foreach ($data->person as $person) {

                if(!Customer::find($person->personid)){
                    Customer::create([
                        'id' => $person->personid,
                        'name' => $person->personname,
                    ]);
                }

                if(is_array($person->phones->phone)) {
                    foreach ($person->phones->phone as $ph) {
                        CustomerPhone::create([
                            'customer_id' => $person->personid,
                            'number' => $ph
                        ]);
                    }
                } else {
                    CustomerPhone::create([
                        'customer_id' => $person->personid,
                        'number' => $person->phones->phone
                    ]);
                }
            }
            DB::commit();
            return true;
        } catch(\Exception $e) {
            Log::info($e->getTraceAsString());
            DB::rollBack();
            return false;
        }
    }

    public function all()
    {
        return Customer::all()->load(['orders', 'phones', 'orders.address', 'orders.items']);
    }

    public function get($id)
    {
        return Customer::find($id)->load(['orders', 'phones', 'orders.address', 'orders.items']);
    }
}
