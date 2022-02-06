<?php

namespace Tests\Support\app;

use App\Business\Character;
use CodeIgniter\Test\CIUnitTestCase;
use App\Business\Traits\Tests;

class CharacterBuilderTraitTest extends CIUnitTestCase
{
    use Tests;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetValidValueAInRange()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidValue', [Character::$MIN_VALUE + 1]);

        $this->assertEquals(Character::$MIN_VALUE +1, $test);
    }

    public function testGetValidValueALowerThanMin()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidValue', [Character::$MIN_VALUE - 1]);

        $this->assertEquals(Character::$MIN_VALUE, $test);
    }

    public function testGetValidValueABiggerThanMax()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidValue', [Character::$MAX_VALUE + 1]);

        $this->assertEquals(Character::$MAX_VALUE, $test);
    }

    //------------------------------------------------------------------------------------------------------------------

    public function testGetValidChanceValueAInRange()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidChance', [Character::$MIN_VALUE + 1]);

        $this->assertEquals(Character::$MIN_VALUE +1, $test);
    }

    public function testGetValidChanceValueALowerThanMin()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidChance', [Character::$MIN_VALUE - 1]);

        $this->assertEquals(Character::$MIN_VALUE, $test);
    }

    public function testGetValidChanceValueABiggerThanMax()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidChance', [Character::$MAX_VALUE_CHANCE + 1]);

        $this->assertEquals(Character::$MAX_VALUE_CHANCE, $test);
    }

    //------------------------------------------------------------------------------------------------------------------

    public function testGetValidTypeValueAInRange()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidType', [Character::$MIN_VALUE + 1]);

        $this->assertEquals(Character::$MIN_VALUE +1, $test);
    }

    public function testGetValidTypeValueALowerThanMin()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidType', [Character::$MIN_VALUE - 1]);

        $this->assertEquals(Character::$MIN_VALUE, $test);
    }

    public function testGetValidTypeValueABiggerThanMax()
    {
        $character = new Character();
        $test = $this->callMethod($character, 'getValidType', [Character::$MAX_VALUE_TYPE + 1]);

        $this->assertEquals(Character::$MAX_VALUE_TYPE, $test);
    }

    //------------------------------------------------------------------------------------------------------------------

    public function testGetRandomValueASmallerThanB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MIN_VALUE, Character::$MAX_VALUE);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE);
    }

    public function testGetRandomValueABiggerThanB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE, Character::$MIN_VALUE);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE);
    }

    public function testGetRandomValueAEqualsB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE, Character::$MAX_VALUE);

        $this->assertEquals(Character::$MAX_VALUE, $test);
    }

    public function testGetRandomValueOutOfRangeMin()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MIN_VALUE - 2, Character::$MIN_VALUE - 1);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE);
    }

    public function testGetRandomValueOutOfRangeMax()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE + 1, Character::$MAX_VALUE + 2);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE);
    }

    //------------------------------------------------------------------------------------------------------------------

    public function testGetRandomValueChanceASmallerThanB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MIN_VALUE, Character::$MAX_VALUE_CHANCE, true);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE_CHANCE);
    }

    public function testGetRandomValueChanceABiggerThanB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE_CHANCE, Character::$MIN_VALUE, true);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE_CHANCE);
    }

    public function testGetRandomValueChanceAEqualsB()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE_CHANCE, Character::$MAX_VALUE_CHANCE, true);

        $this->assertEquals(Character::$MAX_VALUE_CHANCE, $test);
    }

    public function testGetRandomValueChanceOutOfRangeMin()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MIN_VALUE - 2, Character::$MIN_VALUE - 1, true);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE_CHANCE);
    }

    public function testGetRandomValueChanceOutOfRangeMax()
    {
        $character = new Character();
        $test = $character->getRandomValue(Character::$MAX_VALUE_CHANCE + 1, Character::$MAX_VALUE_CHANCE + 2, true);

        $this->assertTrue($test >= Character::$MIN_VALUE && $test <= Character::$MAX_VALUE_CHANCE);
    }
}
