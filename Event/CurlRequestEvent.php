<?php declare(strict_types=1);

namespace Salient\Curler\Event;

use Psr\Http\Message\RequestInterface;
use Salient\Contract\Curler\Event\CurlRequestEventInterface;
use Salient\Contract\Curler\CurlerInterface;
use CurlHandle;

/**
 * @internal
 */
class CurlRequestEvent extends AbstractCurlEvent implements CurlRequestEventInterface
{
    protected RequestInterface $Request;

    /**
     * @param CurlHandle|resource $curlHandle
     */
    public function __construct(CurlerInterface $curler, $curlHandle, RequestInterface $request)
    {
        $this->Request = $request;

        parent::__construct($curler, $curlHandle);
    }

    public function getRequest(): RequestInterface
    {
        return $this->Request;
    }
}
