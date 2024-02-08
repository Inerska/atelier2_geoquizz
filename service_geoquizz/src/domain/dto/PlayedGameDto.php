<?php

declare(strict_types=1);

namespace geoquizz\service\domain\dto;

use AllowDynamicProperties;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;

#[AllowDynamicProperties]
final class PlayedGameDto extends Dto
{
    public function __construct(PlayedGame $playedGame)
    {
        $this->id = $playedGame->getId();
        $this->game_id = $playedGame->getGameId();
        $this->profile_id = $playedGame->getProfileId();
        $this->score = $playedGame->getScore();
        $this->time = $playedGame->getTime();
        $this->distance = $playedGame->getDistance();
        $this->advancement = $playedGame->getAdvancement();
        $this->status = $playedGame->getStatus();
    }
}
