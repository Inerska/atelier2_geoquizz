<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
class PlayedGame
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column]
    private int $id;

    #[ManyToOne(targetEntity: Game::class)]
    #[JoinColumn(name: 'game_id', referencedColumnName: 'id', unique: false, nullable: false)]
    private ?Game $game = null;

    #[ManyToOne(targetEntity: Profile::class)]
    #[JoinColumn(name: 'profile_id', referencedColumnName: 'id', nullable: false)]
    private ?Profile $profile = null;

    #[Column]
    private int $score;
    #[Column]
    private int $time;
    #[Column]
    private int $distance;
    #[Column]
    private int $advancement;
    #[Column]
    private int $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Game|null
     */
    public function getGame(): ?Game
    {
        return $this->game;
    }

    /**
     * @param Game|null $game
     */
    public function setGame(?Game $game): void
    {
        $this->game = $game;
    }

    /**
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile|null $profile
     */
    public function setProfile(?Profile $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     */
    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return int
     */
    public function getAdvancement(): int
    {
        return $this->advancement;
    }

    /**
     * @param int $advancement
     */
    public function setAdvancement(int $advancement): void
    {
        $this->advancement = $advancement;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

}