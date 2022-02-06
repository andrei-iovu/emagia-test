<?php

namespace App\Business;

use App\Business\Traits\CharacterBuilder;

class Character
{
    use CharacterBuilder;

    private $health = 0;
    private $strength = 0;
    private $defence = 0;
    private $speed = 0;
    private $luck = 0;
    private $skills = [];

    private $mapStats = [
        'health' => 'Health',
        'strength' => 'Strength',
        'defence' => 'Defence',
        'speed' => 'Speed',
        'luck' => 'Luck',
    ];

    /**
     * @param int $value
     */
    public function setHealth(int $value): void
    {
        $this->health = $this->getValidValue($value);
    }

    /**
     * @param int $value
     */
    public function setStrength(int $value): void
    {
        $this->strength = $this->getValidValue($value);
    }

    /**
     * @param int $value
     */
    public function setDefence(int $value): void
    {
        $this->defence = $this->getValidValue($value);
    }

    /**
     * @param int $value
     */
    public function setSpeed(int $value): void
    {
        $this->speed = $this->getValidValue($value);
    }

    /**
     * @param int $value
     */
    public function setLuck(int $value): void
    {
        $this->luck = $this->getValidChance($value);
    }

    /**
     * @param string $name
     * @param int $chance
     * @param bool $attack
     */
    public function setSkill(string $name, int $chance, float $multiplier, int $type): void
    {
        $skill = new Skill($name, $chance, $multiplier, $type);
        $this->skills[$skill->getType()][] = $skill;
    }

    /**
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @return int
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return int
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * @return array
     */
    public function getSkills()
    {
        return $this->skills;
    }

    public function getSkillsByType(int $type)
    {
        if (isset($this->skills[$type])) {
            return $this->skills[$type];
        }
        return [];
    }

    public function listStats()
    {
        $parts = [];
        foreach ($this->mapStats as $camp => $label) {
            $parts[] = $label . ': ' . $this->$camp . ($camp == 'luck' ? '%' : '');
        }
        return implode(', ', $parts);
    }

    public function listSkills()
    {
        $parts = [];
        foreach ($this->skills as $skills) {
            foreach ($skills as $skill) {
                $parts[] = $skill->list();
            }
        }
        return $parts;
    }
}