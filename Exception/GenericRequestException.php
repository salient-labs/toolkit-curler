<?php declare(strict_types=1);

namespace Salient\Curler\Exception;

use Psr\Http\Message\RequestInterface;
use Salient\Core\Exception\Exception;
use Salient\Http\HttpRequest;
use Salient\Utility\Format;
use Throwable;

/**
 * @api
 */
class GenericRequestException extends Exception
{
    protected RequestInterface $Request;
    /** @var array<string,mixed> */
    protected array $Data;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(
        string $message,
        RequestInterface $request,
        array $data = [],
        ?Throwable $previous = null
    ) {
        $this->Request = $request;
        $this->Data = $data;

        parent::__construct($message, $previous);
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->Request;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'Request' => (string) HttpRequest::fromPsr7($this->Request),
            'Data' => $this->Data
                ? Format::array($this->Data)
                : '<none>',
        ];
    }
}
