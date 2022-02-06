<?php

namespace App\Business;

use App\Business\Interfaces\BattleBuilder;

class BuildBattle
{
    /**
     * @var BattleBuilder
     */
    private $builder;

    public function setBuilder(BattleBuilder $builder): void
    {
        $this->builder = $builder;
    }

    public function buildStandardBattle(Character $hero, Character $beast, int $rounds): void
    {
        $this->builder->buildInitBattle($hero, $beast, $rounds);
        $this->builder->buildBattle();
    }
}