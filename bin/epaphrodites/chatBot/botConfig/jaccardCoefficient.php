<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

use ArrayIterator;

trait jaccardCoefficient
{

    /**
     * Calculates the Jaccard coefficient between two arrays.
     *
     * @param array $set1 The first array.
     * @param array $set2 The second array.
     * @return float The Jaccard coefficient value.
     */
    private function calculateJaccardCoefficient(array $set1, array $set2): float
    {
        // Create iterators to remove duplicates and count occurrences
        $set1Iterator = new ArrayIterator(array_unique($set1));
        $set2Iterator = new ArrayIterator(array_unique($set2));
    
        // Initialize intersection and union counts
        $intersection = 0;
        $union = 0;
    
        $countSet1 = [];
        foreach ($set1Iterator as $element) {
            if (!isset($countSet1[$element])) {
                $countSet1[$element] = 1;
                $union++;
            } else {
                $countSet1[$element]++;
            }
        }
    
        // Calculate the intersection and union counts with set2
        foreach ($set2Iterator as $element) {
            if (isset($countSet1[$element]) && $countSet1[$element] > 0) {
                $intersection++;
                $countSet1[$element]--;
            } else {
                $union++;
            }
        }
    
        // Calculate the Jaccard coefficient
        $jaccardCoefficient = ($union !== 0) ? $intersection / $union : 0;
    
        return $jaccardCoefficient;
    }   
}
