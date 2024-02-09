<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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

    #[OneToOne(mappedBy: 'actualGame', targetEntity: PlayedGame::class)]
    #[JoinColumn(name: 'actualgame_id', referencedColumnName: 'id', unique: false, nullable: true)]
    private ?PlayedGame $actualGame;

    #[JoinTable(name: 'profile_savedgames')]
    #[JoinColumn(name: 'profile_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'playedgame_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: PlayedGame::class)]
    private Collection $savedGames;


    public function __construct()
    {
        $this->savedGames = new ArrayCollection();
    }

    public function addSavedGame(PlayedGame $playedGame): void
    {
        if (!$this->savedGames->contains($playedGame)) {
            $this->savedGames->add($playedGame);
            $playedGame->setProfile($this);
        }
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
     * @return Game
     */
    public function getActualGame(): ?PlayedGame
    {
        return $this->actualGame;
    }

    /**
     * @param Game $actualGame
     */
    public function setActualGame(?PlayedGame $actualGame): void
    {
        $this->actualGame = $actualGame;
    }

    /**
     * @return Collection
     */
    public function getSavedGames(): Collection
    {
        return $this->savedGames;
    }

    /**
     * @param Collection $savedGames
     */
    public function setSavedGames(Collection $savedGames): void
    {
        $this->savedGames = $savedGames;
    }

}