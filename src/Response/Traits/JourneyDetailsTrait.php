<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

trait JourneyDetailsTrait
{
    /**
     * Url to the journey detail.
     */
    public ?string $journeyDetails = null;

    /**
     * @phpstan-param array<array-key, mixed> $ref
     */
    public function setJourneyDetailRef(array $ref): self
    {
        if (isset($ref['ref'])) {
            $this->journeyDetails = $ref['ref'];
        }

        return $this;
    }
}
