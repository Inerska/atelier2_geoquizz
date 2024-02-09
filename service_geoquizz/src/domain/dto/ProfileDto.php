<?php

declare(strict_types=1);

namespace geoquizz\service\domain\dto;

use AllowDynamicProperties;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;

#[AllowDynamicProperties] class ProfileDto
{
    public int $id;
    public string $username;
    public ?array $actualGame = null;
    public array $savedGames = [];

    public function __construct(Profile $profile)
    {
        $this->id = $profile->getId();
        $this->username = $profile->getUsername();
        $this->scoreTotal = array_reduce($profile->getSavedGames()->toArray(), function ($carry, $playedGame) {
            if ($playedGame instanceof PlayedGame) {
                return $carry + $playedGame->getScore();
            }
            return $carry;
        }, 0);

        $this->highScore = array_reduce($profile->getSavedGames()->toArray(), function ($carry, $playedGame) {
            if ($playedGame instanceof PlayedGame) {
                return max($carry, $playedGame->getScore());
            }
            return $carry;
        }, 0);

        if ($profile->getActualGameId() !== null) {
            $this->actualGame = [
                'id' => $profile->getActualGameId(),
            ];
        }

        foreach ($profile->getSavedGames() as $playedGame) {
            if ($playedGame instanceof PlayedGame) {
                $this->savedGames[] = [
                    'id' => $playedGame->getId(),
                    'score' => $playedGame->getScore(),
                    'time' => $playedGame->getTime(),

                ];
            }
        }
    }
}
