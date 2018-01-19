<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Request as RequestModel;

class Request extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\Request
     */
    public function requestModel()
    {
        $request = new RequestModel();
        $request->address = $this->faker->addressModel();
        $request->person = $this->faker->personModel();
        $request->amount = $this->faker->randomFloat(2, 1, 10);
        $request->merchantPaymentCode = md5(time());

        return $request;
    }
}
