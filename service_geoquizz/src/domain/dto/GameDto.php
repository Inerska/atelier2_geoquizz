<?php

declare(strict_types=1);

namespace geoquizz\service\domain\dto;

use AllowDynamicProperties;
use geoquizz\service\infrastructure\persistence\entity\Game;

#[AllowDynamicProperties] final class GameDto extends Dto
{
    public function __construct(Game $game)
    {
        $this->id = $game->getId();
        $this->serie_id = $game->getSerieId();
        $this->level_id = $game->getLevelId();
        $this->photos = $game->getPhotos();
        $this->isPublic = $game->isPublic();
    }
}