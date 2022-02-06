<?php


namespace App\Business\Traits;

trait CharacterBuilder
{
    public static $MIN_VALUE = 0;
    public static $MAX_VALUE = 1000;
    public static $MAX_VALUE_CHANCE = 100;
    public static $MAX_VALUE_TYPE = 1;

    /**
     * @param int $value
     * @return int
     */
    private function getValidValue(int $value): int
    {
        if ($value < static::$MIN_VALUE) {
            $value = static::$MIN_VALUE;
        }
        if ($value > static::$MAX_VALUE) {
            $value = static::$MAX_VALUE ;
        }

        return $value;
    }

    /**
     * @param int $value
     * @return int
     */
    private function getValidChance(int $value): int
    {
        if ($value < static::$MIN_VALUE) {
            $value = static::$MIN_VALUE;
        }
        if ($value > static::$MAX_VALUE_CHANCE) {
            $value = static::$MAX_VALUE_CHANCE;
        }

        return $value;
    }

    /**
     * @param int $value
     * @return int
     */
    private function getValidType(int $value): int
    {
        if ($value < static::$MIN_VALUE) {
            $value = static::$MIN_VALUE;
        }
        if ($value > static::$MAX_VALUE_TYPE) {
            $value = static::$MAX_VALUE_TYPE;
        }

        return $value;
    }

    /**
     * @param int $min
     * @param int $max
     * @return int[]
     */
    private function getValidRange(int $min, int $max, bool $useChance = false): array
    {
        $min = $useChance ? $this->getValidChance($min) : $this->getValidValue($min);
        $max = $useChance ? $this->getValidChance($max) : $this->getValidValue($max);

        if ($max < $min) {
            $aux = $max;
            $max = $min;
            $min = $aux;
        }

        return [$min, $max];
    }

    /**
     * @param int $min
     * @param int $max
     * @param bool $useChance
     * @return int
     * @throws \Exception
     */
    public function getRandomValue(int $min, int $max, bool $useChance = false): int
    {
        list($min, $max) = $this->getValidRange($min, $max, $useChance);
        return random_int($min, $max);
    }
}