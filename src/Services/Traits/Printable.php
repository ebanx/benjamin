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
        $domain = $this->getDomain($isSandbox);
        $urlFormat = $this->getUrlFormat();
        return sprintf($urlFormat, $domain, $hash);
    }

    /**
     * @param $isSandbox
     * @return string
     */
    private function getDomain($isSandbox)
    {
        if ($isSandbox === null) {
            $isSandbox = $this->config->isSandbox;
        }

        $domain = 'print';
        if ($isSandbox) {
            $domain = 'sandbox';
        }
        return $domain;
    }

    /**
     * @return string
     */
    abstract protected function getUrlFormat();
}
