<?php

namespace App\Controllers;

use App\Business\BuildBattle;
use App\Business\BuildCharacters;
use App\Business\Character;
use App\Business\ConcreteBattleBuilder;
use App\Business\ConcreteCharacterBuilder;

class Home extends BaseController
{
    public function index()
    {
        list($hero, $beast) = $this->buildCharacters();
        $data = [
            'hero_stats' => $hero->listStats(),
            'hero_skills' => $hero->listSkills(),
            'beast_stats' => $beast->listStats(),
            'beast_skills' => $beast->listSkills(),
        ];

        $data['log_actions'] = $this->buildBattle($hero, $beast);

        return view('battle', $data);
    }

    private function buildCharacters()
    {
        $director = new BuildCharacters();
        $builder = new ConcreteCharacterBuilder();
        $director->setBuilder($builder);

        $director->buildOrdeusHero();
        $hero = $builder->getCharacter();

        $director->buildBeast();
        $beast = $builder->getCharacter();

        return [$hero, $beast];
    }

    private function buildBattle(Character $hero, Character $beast)
    {
        $director = new BuildBattle();
        $builder = new ConcreteBattleBuilder();
        $director->setBuilder($builder);

        $director->buildStandardBattle($hero, $beast, 20);
        return $builder->getBattleActions();
    }
}
