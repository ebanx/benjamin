<?php
namespace Ebanx\Benjamin\Services\Traits;

trait Printable
{
    /**
     * @param string $hash
     * @param boolean   $isSandbox
     * @return string
     */
    public function getUrl($hash, $isSandbox = null)
    {
        return sprintf($this->getUrlFormat(), $this->getDomain($isSandbox), $hash);
    }

    /**
     * @param bool $isSandbox
     * @return string
     */
    private function getDomain($isSandbox = null)
    {
        if ($isSandbox === null) {
            $isSandbox = $this->config->isSandbox;
        }

        return $isSandbox ? 'sandbox' : 'print';
    }

    /**
     * @return string
     */
    abstract protected function getUrlFormat();
}
