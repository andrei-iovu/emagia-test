<?php

namespace App\Business;

use App\Business\Traits\CharacterBuilder;

class Skill
{
    use CharacterBuilder;

    const DEFENSE_ID = 0;
    const ATTACK_ID = 1;
    const ATTACK_MIN_MULTIPLIER = 1.0;
    const ATTACK_MAX_MULTIPLIER = 10.0;
    const DEFENSE_MIN_MULTIPLIER = 0.0;
    const DEFENSE_MAX_MULTIPLIER = 1.0;

    private $name = '';
    private $chance = 0;
    private $multiplier = 0.0;
    private $type = 0;

    public static $mapType = [
        self::DEFENSE_ID => 'Defense',
        self::ATTACK_ID => 'Attack',
    ];

    private $mapStats = [
        'name' => 'Name',
        'chance' => 'Chance',
        'multiplier' => 'Damage multiplier',
        'type' => 'Type',
    ];

    public function __construct(string $name, int $chance, float $multiplier, int $type)
    {
        $name = trim($name);
        if(empty($name)){
            throw new \Exception('Empty skill name!');
        }

        $type = $this->getValidType($type);
        if (!isset(static::$mapType[$type])) {
            throw new \Exception('Invalid skill type!');
        }

        $this->name = $name;
        $this->type = $type;
        $this->chance = $this->getValidChance($chance);
        $this->multiplier = $this->getValidMultiplier($multiplier, $type);
    }

    /**
     * @param float $value
     * @param int $type
     * @return float
     */
    private function getValidMultiplier(float $value, int $type): float
    {
        if ($type == self::DEFENSE_ID) {
            if ($value < self::DEFENSE_MIN_MULTIPLIER) {
                $value = self::DEFENSE_MIN_MULTIPLIER;
            }
            if ($value > self::DEFENSE_MAX_MULTIPLIER) {
                $value = self::DEFENSE_MAX_MULTIPLIER;
            }
            $value = 1 - $value;
        } else {
            if ($value < self::ATTACK_MIN_MULTIPLIER) {
                $value = self::ATTACK_MIN_MULTIPLIER;
            }
            if ($value > self::ATTACK_MAX_MULTIPLIER) {
                $value = self::ATTACK_MAX_MULTIPLIER;
            }
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getChance()
    {
        return $this->chance;
    }

    public function getMultiplier()
    {
        return $this->multiplier;
    }

    public function getType()
    {
        return $this->type;
    }

    public static function getTypeLabel(int $type)
    {
        return static::$mapType[$type] ?? 'N/A';
    }

    public function list()
    {
        $parts = [];
        foreach ($this->mapStats as $camp => $label) {
            $value = $this->$camp;
            if($camp == 'name'){
                $value = '"' . $value . '"';
            }
            if($camp == 'multiplier'){
                $value = (($this->$camp - 1) * 100) . '%';
                if($this->type == self::DEFENSE_ID) {
                    $label = 'Damage reduction';
                    $value = ($this->$camp * 100) . '%';
                }
            }
            if($camp == 'type'){
                $value = self::getTypeLabel($this->$camp);
            }
            if($camp == 'chance'){
                $value = $value . '%';
            }
            $parts[] = $label . ': ' . $value;
        }
        return implode(', ', $parts);
    }
}