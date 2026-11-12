<?php

use PHPUnit\Framework\MockObject\Invocation;
use \PHPUnit\Framework\MockObject\Stub\Stub;

class ConsecutiveCalls implements Stub
{
    private int $numberOfConsecutiveCalls = 0;

    public function __construct(
        private mixed $first,
        private mixed $default,
    ) {}

    public function invoke(Invocation $invocation): mixed
    {
        return $this->numberOfConsecutiveCalls++ == 1
            ? $this->first
            : $this->default;
    }
}