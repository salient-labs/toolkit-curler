<?php declare(strict_types=1);

namespace Salient\Curler\Pager;

use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Salient\Contract\Curler\CurlerInterface;
use Salient\Contract\Curler\CurlerPageInterface;
use Salient\Contract\Curler\CurlerPagerInterface;
use Salient\Contract\Http\Message\ResponseInterface;
use Salient\Curler\CurlerPage;
use Salient\Http\HttpUtil;
use Salient\Http\Uri;
use Salient\Utility\Exception\InvalidArgumentTypeException;

/**
 * Follows OData "nextLink" annotations in responses from the endpoint
 *
 * @api
 */
final class ODataPager implements CurlerPagerInterface
{
    private ?int $MaxPageSize;

    /**
     * @api
     */
    public function __construct(?int $maxPageSize = null)
    {
        $this->MaxPageSize = $maxPageSize;
    }

    /**
     * @inheritDoc
     */
    public function getFirstRequest(
        PsrRequestInterface $request,
        CurlerInterface $curler,
        ?array $query = null
    ): PsrRequestInterface {
        if ($this->MaxPageSize === null) {
            return $request;
        }

        $prefs = HttpUtil::getPreferences($request);
        if (
            isset($prefs['odata.maxpagesize'])
            && $prefs['odata.maxpagesize']['value'] === (string) $this->MaxPageSize
        ) {
            return $request;
        }

        $prefs['odata.maxpagesize']['value'] = (string) $this->MaxPageSize;

        return $request->withHeader(
            self::HEADER_PREFER,
            HttpUtil::mergePreferences($prefs),
        );
    }

    /**
     * @inheritDoc
     */
    public function getPage(
        $data,
        PsrRequestInterface $request,
        ResponseInterface $response,
        CurlerInterface $curler,
        ?CurlerPageInterface $previousPage = null,
        ?array $query = null
    ): CurlerPageInterface {
        if (!is_array($data)) {
            throw new InvalidArgumentTypeException(1, 'data', 'mixed[]', $data);
        }
        /** @var array{'@odata.nextLink'?:string,'@nextLink'?:string,value:list<mixed>,...} $data */
        if ($response->getHeaderLine(self::HEADER_ODATA_VERSION) === '4.0') {
            $nextLink = $data['@odata.nextLink'] ?? null;
        } else {
            $nextLink = $data['@nextLink'] ?? $data['@odata.nextLink'] ?? null;
        }

        return new CurlerPage(
            $data['value'],
            $nextLink === null
                ? null
                : $request->withUri(new Uri($nextLink))
        );
    }
}
