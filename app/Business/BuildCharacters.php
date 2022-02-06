<?php

namespace App\Business;

use App\Business\Interfaces\CharacterBuilder;

class BuildCharacters
{
    /**
     * @var CharacterBuilder
     */
    private $builder;

    public function setBuilder(CharacterBuilder $builder): void
    {
        $this->builder = $builder;
    }

    public function buildOrdeusHero(): void
    {
        $this->builder->buildHealth(70, 100);
        $this->builder->buildStrength(70, 80);
        $this->builder->buildDefence(45, 55);
        $this->builder->buildSpeed(40, 50);
        $this->builder->buildLuck(10, 30);
        $this->builder->buildSkill('Rapid Strike', 10, 2, Skill::ATTACK_ID);
        $this->builder->buildSkill('Magic Shield', 20, 0.5, Skill::DEFENSE_ID);
    }

    public function buildBeast(): void
    {
        $this->builder->buildHealth(60, 90);
        $this->builder->buildStrength(60, 90);
        $this->builder->buildDefence(40, 60);
        $this->builder->buildSpeed(40, 60);
        $this->builder->buildLuck(25, 40);
    }
}