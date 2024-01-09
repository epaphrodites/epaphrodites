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
        // Convert arrays to sets to remove duplicates
        $set1 = array_unique($set1);
        $set2 = array_unique($set2);
        
        // Calculate the size of the intersection of the two arrays
        $intersection = count(array_intersect($set1, $set2));
        
        // Calculate the size of the union of the two arrays (using set2 as reference)
        $union = count($set2);

        // Calculate the Jaccard coefficient
        $jaccardCoefficient = ($union !== 0) ? $intersection / $union : 0;

        return $jaccardCoefficient;
    }


    

#var_dump($jaccardCoefficient);die;

}
