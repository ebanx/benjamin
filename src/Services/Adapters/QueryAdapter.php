<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;

class QueryAdapter extends BaseAdapter
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $code;

    /**
     * QueryAdapter constructor.
     *
     * @param string $type
     * @param string $code
     * @param Config $config
     */
    public function __construct($type, $code, Config $config)
    {
        $this->type = $type;
        $this->code = $code;
        parent::__construct($config);
    }

    public function transform()
    {
        return array(
            'integration_key' => $this->getIntegrationKey(),
            $this->type => $this->code
        );
    }
}
