<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;

class RefundAdapter extends BaseAdapter
{
    /**
     * @var array
     */
    private $data;

    /**
     * RefundAdapter constructor.
     *
     * @param array $data
     * @param Config $config
     */
    public function __construct($data, Config $config)
    {
        $this->data = $data;
        parent::__construct($config);
    }

    public function transform()
    {
        $transformed = array(
            'integration_key' => $this->getIntegrationKey(),
            'operation' => 'request',
            'amount' => $this->data['amount'],
            'description' => $this->data['description'],
        );
        if (isset($this->data['hash'])) {
            $transformed['hash'] = $this->data['hash'];
        }

        if (isset($this->data['merchantRefundCode'])) {
            $transformed['merchant_refund_code'] = $this->data['merchantRefundCode'];
        }

        return $transformed;
    }

    public function transformCancel()
    {
        return array(
            'integration_key' => $this->getIntegrationKey(),
            'operation' => 'cancel',
            'refund_id' => $this->data['refundId']
        );
    }
}
