<?php

namespace App\Business\Interfaces;

use App\Business\Character;

interface BattleBuilder
{
    public function buildInitBattle(Character $hero, Character $beast, int $rounds);
    public function buildBattle();
}