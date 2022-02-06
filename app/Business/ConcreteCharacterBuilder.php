<?php


namespace App\Business;

use App\Business\Interfaces\CharacterBuilder;

class ConcreteCharacterBuilder implements CharacterBuilder
{
    /* @var Character */
    private $character;


    public function __construct()
    {
        $this->reset();
    }

    private function reset(): void
    {
        $this->character = new Character();
    }

    /**
     * @param int $min
     * @param int $max
     * @throws \Exception
     */
    public function buildHealth(int $min, int $max): void
    {
        while ($this->character->getHealth() <= 0) {
            $this->character->setHealth($this->character->getRandomValue($min, $max));
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @throws \Exception
     */
    public function buildStrength(int $min, int $max): void
    {
        while ($this->character->getStrength() <= 0) {
            $this->character->setStrength($this->character->getRandomValue($min, $max));
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @throws \Exception
     */
    public function buildDefence(int $min, int $max): void
    {
        while ($this->character->getDefence() <= 0) {
            $this->character->setDefence($this->character->getRandomValue($min, $max));
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @throws \Exception
     */
    public function buildSpeed(int $min, int $max): void
    {
        while ($this->character->getSpeed() <= 0) {
            $this->character->setSpeed($this->character->getRandomValue($min, $max));
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @throws \Exception
     */
    public function buildLuck(int $min, int $max): void
    {
        $this->character->setLuck($this->character->getRandomValue($min, $max, true));
    }

    /**
     * @param string $name
     * @param int $chance
     * @param float $multiplier
     * @param int $type
     */
    public function buildSkill(string $name, int $chance, float $multiplier, int $type)
    {
        $this->character->setSkill($name, $chance, $multiplier, $type);
    }

    /**
     * @return Character
     */
    public function getCharacter(): Character
    {
        $result = $this->character;
        $this->reset();

        return $result;
    }
}