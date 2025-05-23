<?php declare(strict_types=1);

namespace Salient\Curler\Event;

use Salient\Contract\Curler\Event\CurlerEvent as CurlerEventInterface;
use Salient\Contract\Curler\CurlerInterface;

/**
 * @internal
 */
abstract class CurlerEvent implements CurlerEventInterface
{
    protected CurlerInterface $Curler;

    public function __construct(CurlerInterface $curler)
    {
        $this->Curler = $curler;
    }

    /**
     * @inheritDoc
     */
    public function getCurler(): CurlerInterface
    {
        return $this->Curler;
    }
}
