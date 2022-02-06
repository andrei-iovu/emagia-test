<?php

namespace Tests\Support\Business;

use App\Business\Character;
use App\Business\ConcreteBattleBuilder;
use App\Business\ConcreteCharacterBuilder;
use App\Business\Skill;
use App\Business\Traits\Tests;
use CodeIgniter\Test\CIUnitTestCase;

class ConcreteBattleBuilderTest extends CIUnitTestCase
{
    use Tests;

    /* @var Character */
    private $hero1;

    /* @var Character */
    private $hero2;

    /* @var Character */
    private $beast1;

    /* @var Character */
    private $beast2;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero1 = $this->buildHero1();
        $this->beast1 = $this->buildBeast1();
        $this->hero2 = $this->buildHero2();
        $this->beast2 = $this->buildBeast2();
    }

    private function buildHero1()
    {
        $builder = new ConcreteCharacterBuilder();

        $builder->buildHealth(100, 100);
        $builder->buildStrength(80, 80);
        $builder->buildDefence(55, 55);
        $builder->buildSpeed(50, 50);
        $builder->buildLuck(0, 0);
        $builder->buildSkill('Rapid Strike', 100, 2, Skill::ATTACK_ID);
        $builder->buildSkill('Magic Shield', 100, 0.5, Skill::DEFENSE_ID);

        return $builder->getCharacter();
    }

    private function buildHero2()
    {
        $builder = new ConcreteCharacterBuilder();

        $builder->buildHealth(70, 70);
        $builder->buildStrength(70, 70);
        $builder->buildDefence(45, 45);
        $builder->buildSpeed(40, 40);
        $builder->buildLuck(0, 0);
        $builder->buildSkill('Rapid Strike', 100, 2, Skill::ATTACK_ID);
        $builder->buildSkill('Magic Shield', 100, 0.5, Skill::DEFENSE_ID);

        return $builder->getCharacter();
    }

    private function buildBeast1()
    {
        $builder = new ConcreteCharacterBuilder();

        $builder->buildHealth(90, 90);
        $builder->buildStrength(90, 90);
        $builder->buildDefence(60, 60);
        $builder->buildSpeed(60, 60);
        $builder->buildLuck(0, 0);

        return $builder->getCharacter();
    }

    private function buildBeast2()
    {
        $builder = new ConcreteCharacterBuilder();

        $builder->buildHealth(60, 60);
        $builder->buildStrength(60, 60);
        $builder->buildDefence(40, 40);
        $builder->buildSpeed(30, 30);
        $builder->buildLuck(0, 0);

        return $builder->getCharacter();
    }

    public function testBuildInitBattleAssignedAttacker1()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, 1);
        $test = $builder->getAttacker();
        $this->assertEquals(ConcreteBattleBuilder::BEAST_ID, $test);
    }

    public function testBuildInitBattleAssignedAttacker2()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero2, $this->beast2, 1);
        $test = $builder->getAttacker();
        $this->assertEquals(ConcreteBattleBuilder::HERO_ID, $test);
    }

    public function testBuildInitBattleAssignedDefender1()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, 1);
        $test = $builder->getDefender();
        $this->assertEquals(ConcreteBattleBuilder::HERO_ID, $test);
    }

    public function testBuildInitBattleAssignedDefender2()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero2, $this->beast2, 1);
        $test = $builder->getDefender();
        $this->assertEquals(ConcreteBattleBuilder::BEAST_ID, $test);
    }

    public function testBuildInitBattleAssignedRoundsInRange()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, 10);
        $test = $builder->getRounds();
        $this->assertEquals(10, $test);
    }

    public function testBuildInitBattleAssignedRoundsLowerThanMin()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, Character::$MIN_VALUE - 1);
        $test = $builder->getRounds();
        $this->assertEquals(Character::$MIN_VALUE, $test);
    }

    public function testBuildInitBattleAssignedRoundsBiggerThanMax()
    {
        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, Character::$MAX_VALUE + 1);
        $test = $builder->getRounds();
        $this->assertEquals(Character::$MAX_VALUE, $test);
    }

    public function testBuildFightAttacker1()
    {
        $this->hero1 = $this->buildHero1();
        $this->beast1 = $this->buildBeast1();

        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, 1);

        $damage = $this->callMethod($builder, 'getDamage', [ConcreteBattleBuilder::BEAST_ID]);
        $test = $builder->getHero()->getHealth() - $damage;
        $this->callMethod($builder, 'buildFight', [1, $builder->getAttacker()]);
        $this->assertEquals($builder->getHero()->getHealth(), $test);
    }

    public function testBuildFightDefender1()
    {
        $this->hero1 = $this->buildHero1();
        $this->beast1 = $this->buildBeast1();

        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero1, $this->beast1, 1);
        $this->callMethod($builder, 'buildFight', [1, $builder->getAttacker()]);

        $damage = $this->callMethod($builder, 'getDamage', [ConcreteBattleBuilder::HERO_ID]);
        $test = $builder->getBeast()->getHealth() - $damage;
        $this->callMethod($builder, 'buildFight', [1, $builder->getDefender()]);
        $this->assertEquals($builder->getBeast()->getHealth(), $test);
    }

    public function testBuildFightAttacker2()
    {
        $this->hero2 = $this->buildHero2();
        $this->beast2 = $this->buildBeast2();

        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero2, $this->beast2, 1);

        $damage = $this->callMethod($builder, 'getDamage', [ConcreteBattleBuilder::HERO_ID]);
        $test = $builder->getBeast()->getHealth() - $damage;
        $this->callMethod($builder, 'buildFight', [1, $builder->getAttacker()]);
        $this->assertEquals($builder->getBeast()->getHealth(), $test);
    }

    public function testBuildFightDefender2()
    {
        $this->hero2 = $this->buildHero2();
        $this->beast2 = $this->buildBeast2();

        $builder = new ConcreteBattleBuilder();
        $builder->buildInitBattle($this->hero2, $this->beast2, 1);
        $this->callMethod($builder, 'buildFight', [1, $builder->getAttacker()]);

        $damage = $this->callMethod($builder, 'getDamage', [ConcreteBattleBuilder::BEAST_ID]);
        $test = $builder->getHero()->getHealth() - $damage;
        $this->callMethod($builder, 'buildFight', [1, $builder->getDefender()]);
        $this->assertEquals($builder->getHero()->getHealth(), $test);
    }
}
