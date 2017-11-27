<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Currency as CurrencyModel;

class CurrencyCode extends BaseProvider
{
    const INVALID = 'ABC';

    /**
     * @return string
     */
    public function ebanxCurrencyCode()
    {
        return $this->faker->randomElement([
            CurrencyModel::USD,
            CurrencyModel::EUR,
            CurrencyModel::BRL,
            CurrencyModel::MXN,
            CurrencyModel::PEN,
            CurrencyModel::COP,
            CurrencyModel::CLP,
        ]);
    }

    /**
     * @return string
     */
    public function invalidEbanxCurrencyCode()
    {
        return self::INVALID;
    }
}
