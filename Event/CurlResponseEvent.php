<?php declare(strict_types=1);

namespace Salient\Curler\Event;

use Psr\Http\Message\RequestInterface;
use Salient\Contract\Curler\Event\CurlResponseEventInterface;
use Salient\Contract\Curler\CurlerInterface;
use Salient\Contract\Http\HttpResponseInterface;
use CurlHandle;

/**
 * @internal
 */
class CurlResponseEvent extends AbstractCurlEvent implements CurlResponseEventInterface
{
    protected RequestInterface $Request;
    protected HttpResponseInterface $Response;

    /**
     * @param CurlHandle|resource $curlHandle
     */
    public function __construct(CurlerInterface $curler, $curlHandle, RequestInterface $request, HttpResponseInterface $response)
    {
        $this->Request = $request;
        $this->Response = $response;

        parent::__construct($curler, $curlHandle);
    }

    public function getRequest(): RequestInterface
    {
        return $this->Request;
    }

    public function getResponse(): HttpResponseInterface
    {
        return $this->Response;
    }
}
