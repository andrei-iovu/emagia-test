<?php

namespace App\Business;

use App\Business\Interfaces\BattleBuilder;
use App\Business\Traits\CharacterBuilder;

class ConcreteBattleBuilder implements BattleBuilder
{
    use CharacterBuilder;

    const HERO_ID = 0;
    const BEAST_ID = 1;

    /* @var Character */
    private $hero;

    /* @var Character */
    private $beast;

    private $attacker;
    private $defender;
    private $rounds;
    private $mapAttackCharacter = [];
    private $logActions = [];
    private $logMessage = '';

    public function __construct()
    {
        $this->reset();
    }

    private function reset(): void
    {
        $this->hero = null;
        $this->beast = null;
        $this->mapAttackCharacter = [];
        $this->attacker = $this->defender = -1;
        $this->logMessage = '';
    }

    /**
     * @return Character
     */
    public function getHero()
    {
        return $this->hero;
    }

    /**
     * @return Character
     */
    public function getBeast()
    {
        return $this->beast;
    }

    /**
     * @return mixed
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * @return mixed
     */
    public function getDefender()
    {
        return $this->defender;
    }

    /**
     * @return mixed
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * @param int $rounds
     * @throws \Exception
     */
    public function buildInitBattle(Character $hero, Character $beast, int $rounds)
    {
        $this->hero = $hero;
        $this->beast = $beast;
        $this->mapAttackCharacter = [
            self::HERO_ID => $this->hero,
            self::BEAST_ID => $this->beast,
        ];
        $this->rounds = $this->getValidValue($rounds);

        $this->initAttacker();
        $this->initDefender();

        $this->logAction(
            'init',
            $this->getCharacterLabel($this->attacker) . ' attacks first, then ' .
            $this->getCharacterLabel($this->defender)
        );
    }

    private function initAttacker()
    {
        if($this->hero->getSpeed() > $this->beast->getSpeed()) {
            $this->attacker = self::HERO_ID;
        } elseif ($this->beast->getSpeed() > $this->hero->getSpeed()){
            $this->attacker = self::BEAST_ID;
        } else {
            if ($this->hero->getLuck() > $this->beast->getLuck()) {
                $this->attacker = self::HERO_ID;
            } elseif ($this->beast->getLuck() > $this->hero->getLuck()) {
                $this->attacker = self::BEAST_ID;
            } else {
                $this->attacker = self::HERO_ID;
            }
        }
    }

    private function initDefender()
    {
        $this->defender = $this->attacker == self::BEAST_ID ? self::HERO_ID : self::BEAST_ID;
    }

    /**
     * @throws \Exception
     */
    public function buildBattle()
    {
        $wins = false;
        for ($i = 1; $i <= $this->rounds; $i++) {
            $this->logAction($i, 'Round ' . $i);

            $wins = $this->buildFight($i, $this->attacker);
            if($wins){
                break;
            }

            $wins = $this->buildFight($i, $this->defender);
            if($wins){
                break;
            }
        }

        if(!$wins){
            $this->logAction('win', 'No one wins the battle!!!');
        }
    }

    private function buildFight($section, int $attacker)
    {
        $type = 'Defense:';
        $defender = $this->attacker;
        if ($attacker == $this->attacker) {
            $type = 'Attack:';
            $defender = $this->defender;
        }

        $this->logAction($section, $type);
        if(!$this->getChance($this->mapAttackCharacter[$defender]->getLuck())){
            $this->fight($attacker, $section);
            if($this->characterIsDead($defender)){
                $this->logAction('win', $this->getCharacterLabel($attacker) . ' wins the battle!!!');
                return true;
            }
        } else {
            $this->logAction($section, '  ' . $this->getCharacterLabel($attacker) . ' misses the attack!');
        }
        return false;
    }

    /**
     * @param int $chance
     * @return bool
     * @throws \Exception
     */
    private function getChance(int $chance)
    {
        $luck = random_int(0, 100);
        return $luck > 0 && $luck <= $chance;
    }

    /**
     * @param int $defender
     * @return bool
     */
    private function characterIsDead(int $defender)
    {
        return $this->mapAttackCharacter[$defender]->getHealth() <= 0;
    }

    /**
     * @param int $attacker
     * @param int $section
     */
    private function fight(int $attacker, int $section){
        $attacker == self::BEAST_ID ? $this->beastAttack($section) : $this->heroAttack($section);
    }

    /**
     * @param int $section
     */
    private function heroAttack(int $section)
    {
        $this->logMessage = "  Hero attacks";
        $damage = $this->getDamage(self::HERO_ID);
        $this->beast->setHealth($this->beast->getHealth() - $damage);
        $this->logMessage.= " and did " . $damage . ' damage. The beast has now ' . $this->beast->getHealth() . ' health';
        $this->logAction($section, $this->logMessage);
        $this->logMessage = '';
    }

    /**
     * @param int $section
     */
    private function beastAttack(int $section)
    {
        $this->logMessage = "  Beast attacks";
        $damage = $this->getDamage(self::BEAST_ID);
        $this->hero->setHealth($this->hero->getHealth() - $damage);
        $this->logMessage.= " and did " . $damage . ' damage. The hero has now ' . $this->hero->getHealth() . ' health';
        $this->logAction($section, $this->logMessage);
        $this->logMessage = '';
    }

    private function getDamage(int $attacker)
    {
        $skillType = Skill::ATTACK_ID;
        $defender = self::BEAST_ID;
        if ($attacker == self::BEAST_ID) {
            $skillType = Skill::DEFENSE_ID;
            $defender = self::HERO_ID;
        }
        $damageMultiplier = $this->getDamageMultiplier($skillType);
        return (int)(
            ($this->mapAttackCharacter[$attacker]->getStrength() - $this->mapAttackCharacter[$defender]->getDefence()) *
            $damageMultiplier
        );
    }

    /**
     * @param int $skillType
     * @param string $messsage
     * @return int
     */
    private function getDamageMultiplier(int $skillType)
    {
        $skills = $this->hero->getSkillsByType($skillType);
        $damageMultiplier = 1;
        if (!empty($skills)) {
            foreach ($skills as $skill) {
                if ($this->getChance($skill->getChance())) {
                    $damageMultiplier = $skill->getMultiplier();
                    $this->logMessage.= ' (Hero is using the "' . $skill->getName() . '" skill)';
                    break;
                }
            }
        }

        return $damageMultiplier;
    }

    /**
     * @param int $attacker
     * @return string
     */
    private function getCharacterLabel(int $attacker)
    {
        return $attacker == self::BEAST_ID ? 'Beast' : 'Hero';
    }

    /**
     * @param $section
     * @param string $message
     */
    private function logAction($section, string $message)
    {
        if(!empty(trim($message))) {
            $this->logActions[$section][] = $message;
        }
    }

    /**
     * @return array
     */
    public function getBattleActions()
    {
        $this->reset();
        return $this->logActions;
    }
}