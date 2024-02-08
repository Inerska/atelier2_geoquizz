<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
class Profile
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column]
    private int $id;

    #[Column]
    private string $username;

    #[Column(nullable: true), OneToOne(targetEntity: 'Game')]
    private ?Game $actualGame = null;

    #[Column(name: 'savedGames'), OneToMany(targetEntity: 'PlayedGame')]
    private array $savedGames;

    public function __construct()
    {
        $this->savedGames = [];
    }

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
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return Game|null
     */
    public function getActualGame(): ?Game
    {
        return $this->actualGame;
    }

    /**
     * @param Game|null $actualGame
     */
    public function setActualGame(?Game $actualGame): void
    {
        $this->actualGame = $actualGame;
    }

    /**
     * @return array
     */
    public function getSavedGames(): array
    {
        return $this->savedGames;
    }

    /**
     * @param array $savedGames
     */
    public function setSavedGames(array $savedGames): void
    {
        $this->savedGames = $savedGames;
    }
}