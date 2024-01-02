<?php

namespace Epaphrodites\epaphrodites\bot;

trait jaccardCoefficient
{

    /**
     * Calculates the Jaccard coefficient between two arrays.
     *
     * @param array $set1 The first array.
     * @param array $set2 The second array.
     * @return float The Jaccard coefficient value.
     */
    public function calculateJaccardCoefficient(array $set1, array $set2): float
    {
        // Calculate the size of the intersection of the two arrays
        $intersection = count(array_intersect($set1, $set2));

        // Calculate the size of the union of the two arrays
        $union = count(array_unique([...$set1, ...$set2]));

        // Calculate the Jaccard coefficient
        return $union !== 0 ? $intersection / $union : 0;
    }
}
