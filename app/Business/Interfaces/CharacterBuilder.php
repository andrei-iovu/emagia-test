<?php

namespace App\Business\Interfaces;

interface CharacterBuilder
{
    public function buildHealth(int $min, int $max);
    public function buildStrength(int $min, int $max);
    public function buildDefence(int $min, int $max);
    public function buildSpeed(int $min, int $max);
    public function buildLuck(int $min, int $max);
    public function buildSkill(string $name, int $chance, float $multiplier, int $type);
}