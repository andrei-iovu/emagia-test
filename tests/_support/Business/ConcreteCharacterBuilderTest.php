<?php

namespace Tests\Support\Business;

use App\Business\Character;
use App\Business\ConcreteCharacterBuilder;
use App\Business\Skill;
use App\Business\Traits\Tests;
use CodeIgniter\Test\CIUnitTestCase;

class ConcreteCharacterBuilderTest extends CIUnitTestCase
{
    use Tests;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testBuildHealthValueInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildHealth(Character::$MIN_VALUE, Character::$MAX_VALUE);
        $character = $builder->getCharacter();
        $test = $character->getHealth();
        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE && $test > 0);
    }

    public function testBuildStrengthValueInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildStrength(Character::$MIN_VALUE, Character::$MAX_VALUE);
        $character = $builder->getCharacter();
        $test = $character->getStrength();
        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE && $test > 0);
    }

    public function testBuildDefenceValueInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildDefence(Character::$MIN_VALUE, Character::$MAX_VALUE);
        $character = $builder->getCharacter();
        $test = $character->getDefence();
        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE && $test > 0);
    }

    public function testBuildSpeedValueInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSpeed(Character::$MIN_VALUE, Character::$MAX_VALUE);
        $character = $builder->getCharacter();
        $test = $character->getSpeed();
        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE && $test > 0);
    }

    public function testBuildLuckValueInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildLuck(Character::$MIN_VALUE, Character::$MAX_VALUE_CHANCE);
        $character = $builder->getCharacter();
        $test = $character->getLuck();
        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE_CHANCE);
    }

    public function testBuildSkillAttackInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 10, 2, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills1 = $character->getSkills();
        $skill1 = array_shift($skills1);
        $skill1 = array_shift($skill1);

        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 10, 2, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills2 = $character->getSkills();
        $skill2 = array_shift($skills2);
        $skill2 = array_shift($skill2);

        $this->assertEquals($skill1, $skill2);
    }

    public function testBuildSkillDefenseInRange()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 20, 0.5, Skill::DEFENSE_ID);
        $character = $builder->getCharacter();
        $skills1 = $character->getSkills();

        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 20, 0.5, Skill::DEFENSE_ID);
        $character = $builder->getCharacter();
        $skills2 = $character->getSkills();

        $this->assertEquals($skills1, $skills2);
    }

    public function testBuildSkillAttackInvalidName()
    {
        $builder = new ConcreteCharacterBuilder();
        $test = false;
        try {
            $builder->buildSkill('', 10, 2, Skill::ATTACK_ID);
            $character = $builder->getCharacter();
            $skills = $character->getSkills();
            $skill = array_shift($skills);
            $skill = array_shift($skill);
            if ($skill->getName() === '') {
                $test = true;
            }
        } catch (\Exception $e) {
        }

        $this->assertEquals(false, $test);
    }

    public function testBuildSkillAttackValidName()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 10, 2, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getName();

        $this->assertEquals('Random Skill', $test);
    }

    public function testBuildSkillAttackInvalidLuckBiggerThanMax()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', Skill::$MAX_VALUE_CHANCE + 1, 2, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getChance();

        $this->assertEquals(Skill::$MAX_VALUE_CHANCE, $test);
    }

    public function testBuildSkillAttackInvalidLuckLowerThanMin()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', Skill::$MIN_VALUE - 1, 2, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getChance();

        $this->assertEquals(Skill::$MIN_VALUE, $test);
    }

    public function testBuildSkillInvalidType()
    {
        $builder = new ConcreteCharacterBuilder();
        $test = false;
        try {
            $builder->buildSkill('Random Skill', 10, 2, 2);
            $character = $builder->getCharacter();
            $skills = $character->getSkills();
            $skill = array_shift($skills);
            $skill = array_shift($skill);
            if (!isset(Skill::$mapType[$skill->getType()])) {
                $test = true;
            }
        } catch (\Exception $e) {
        }

        $this->assertEquals(false, $test);
    }

    public function testBuildSkillAttackInvalidMultiplierBiggerThanMax()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 10, Skill::ATTACK_MAX_MULTIPLIER + 1, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getMultiplier();

        $this->assertEquals(Skill::ATTACK_MAX_MULTIPLIER, $test);
    }

    public function testBuildSkillAttackInvalidMultiplierLowerThanMin()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 10, Skill::ATTACK_MIN_MULTIPLIER - 1, Skill::ATTACK_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getMultiplier();

        $this->assertEquals(Skill::ATTACK_MIN_MULTIPLIER, $test);
    }

    public function testBuildSkillDefenseInvalidMultiplierBiggerThanMax()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 20, Skill::DEFENSE_MAX_MULTIPLIER + 1, Skill::DEFENSE_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getMultiplier();

        $this->assertEquals(Skill::DEFENSE_MIN_MULTIPLIER, $test);
    }

    public function testBuildSkillDefenseInvalidMultiplierLowerThanMin()
    {
        $builder = new ConcreteCharacterBuilder();
        $builder->buildSkill('Random Skill', 20, Skill::DEFENSE_MIN_MULTIPLIER - 1, Skill::DEFENSE_ID);
        $character = $builder->getCharacter();
        $skills = $character->getSkills();
        $skill = array_shift($skills);
        $skill = array_shift($skill);
        $test = $skill->getMultiplier();

        $this->assertEquals(Skill::DEFENSE_MAX_MULTIPLIER, $test);
    }
}
