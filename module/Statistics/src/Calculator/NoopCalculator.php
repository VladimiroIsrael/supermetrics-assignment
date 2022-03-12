<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int
     */
    private $postCount = [];

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount[] = $postTo->getAuthorId();
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $noop = $this->postCount > 0 ? count($this->postCount) / count(array_unique($this->postCount)) : 0;
        return (new StatisticsTo())->setValue(round($noop,2));
    }
}
