<?php

declare(strict_types=1);

namespace Phpml\Math\Distance;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Distance;

class Euclidean implements Distance
{
    /**
     * @throws InvalidArgumentException
     */
    public function distance(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            throw new InvalidArgumentException('Size of given arrays does not match');
        }

        $distance = 0;

        foreach ($a as $i => $val) {
            $distance += ($val - $b[$i]) ** 2;
        }

        return sqrt((float) $distance);
    }

    /**
     * Square of Euclidean distance
     */
    public function sqDistance(array $a, array $b): float
    {
        return $this->distance($a, $b) ** 2;
    }
}
