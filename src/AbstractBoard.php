<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use DateTime;
use DateTimeInterface;
use Lsv\Rejseplan\Response\Location\Stop;
use Lsv\Rejseplan\Utils\LocationParser;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBoard extends AbstractRequest
{
    public function __construct(string|int|Stop $location)
    {
        $this->options['id'] = $location;
    }

    public function setDontUseTrain(): self
    {
        $this->options['useTog'] = false;

        return $this;
    }

    public function setDontUseBus(): self
    {
        $this->options['useBus'] = false;

        return $this;
    }

    public function setDontUseMetro(): self
    {
        $this->options['useMetro'] = false;

        return $this;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->options['date'] = $date;

        return $this;
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setRequired('id');
        $resolver->setAllowedTypes('id', LocationParser::supports(false));
        $resolver->setDefined(['date', 'time', 'useTog', 'useBus', 'useMetro']);
        $resolver->addAllowedTypes('date', DateTime::class);
        $resolver->setAllowedTypes('useTog', 'bool');
        $resolver->setAllowedTypes('useBus', 'bool');
        $resolver->setAllowedTypes('useMetro', 'bool');
    }

    public function getQuery(): array
    {
        if (
            isset($this->resolvedOptions['date'])
            && ($date = $this->resolvedOptions['date'])
            && $date instanceof DateTimeInterface
        ) {
            $this->resolvedOptions['date'] = $date->format('d.m.y');
            $this->resolvedOptions['time'] = $date->format('H:i');
        }

        LocationParser::parse($this->resolvedOptions, 'id', false);

        return parent::getQuery();
    }
}
