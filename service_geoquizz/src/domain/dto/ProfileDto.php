<?php

declare(strict_types=1);

namespace geoquizz\service\domain\dto;

use geoquizz\service\infrastructure\persistence\entity\Profile;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;

class ProfileDto
{
    public int $id;
    public string $username;
    public ?array $actualGame = null;
    public array $savedGames = [];

    public function __construct(Profile $profile)
    {
        $this->id = $profile->getId();
        $this->username = $profile->getUsername();

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
