<?php
namespace Ebanx\Benjamin\Models\Configs;

use Ebanx\Benjamin\Models\BaseModel;
use Ebanx\Benjamin\Models\Currency;

class CreditCardConfig extends BaseModel implements AddableConfig
{
    const MAX_INSTALMENTS = 12;

    /**
     * Number of max instalments, defaults to 12.
     *
     * @var integer
     */
    public $maxInstalments = self::MAX_INSTALMENTS;

    /**
     * Minimum instalment amount.
     * Default varies by currency.
     *
     * @var float
     */
    public $minInstalmentAmount = 0.0;

    /**
     * List of interest rate config objects.
     *
     * @var array
     */
    public $interestRates = array();

    public static function acquirerMinInstalmentValueForCurrency($currency)
    {
        $relation = array(
            Currency::BRL => 20,
            Currency::MXN => 100,
            Currency::COP => 100
        );

        return $relation[$currency];
    }

    /**
     * Adds an interest rate config object for the credit card config.
     *
     * @param integer $instalmentNumber The instalment number for this rate configuration
     * @param float   $rate              The interest rate to be applied
     * @return CreditCardConfig itself
     */
    public function addInterest($instalmentNumber, $rate)
    {
        $this->interestRates[] = new CreditCardInterestRateConfig(array(
            "instalmentNumber" => $instalmentNumber,
            "interestRate" => $rate
        ));

        return $this;
    }
}
