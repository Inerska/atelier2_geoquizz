<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
final class PlayedGame
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column]
    private int $id;

    #[Column]
    #[OneToOne(targetEntity: 'Game')]
    private int $game_id;

    #[Column]
    #[OneToOne(targetEntity: 'Profile')]
    private int $profile_id;

    #[Column]
    private int $score;

    #[Column]
    private int $time;

    #[Column]
    private int $distance;

    #[Column]
    private int $advancement;

    #[Column]
    private string $status;
}