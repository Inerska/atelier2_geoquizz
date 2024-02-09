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
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
class Profile
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column]
    private int $id;

    #[Column]
    private string $username;

    #[Column(name: 'actual_game_id', type: 'integer', nullable: true)]
    private ?int $actualGameId = null;

    #[ManyToMany(targetEntity: PlayedGame::class)]
    #[JoinTable(name: 'profile_savedgames')]
    #[JoinColumn(name: 'profile_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'playedgame_id', referencedColumnName: 'id')]
    private Collection $savedGames;

    public function getAvatarName(): string
    {
        return $this->avatar_name;
    }

    public function setAvatarName(string $avatar_name): void
    {
        $this->avatar_name = $avatar_name;
    }

    public function getWallpaperName(): string
    {
        return $this->wallpaper_name;
    }

    public function setWallpaperName(string $wallpaper_name): void
    {
        $this->wallpaper_name = $wallpaper_name;
    }

    #[Column]
    private string $avatar_name;

    #[Column]
    private string $wallpaper_name;

    public function __construct()
    {
        $this->savedGames = new ArrayCollection();
    }

    public function getActualGameId(): ?int
    {
        return $this->actualGameId;
    }

    public function setActualGameId(?int $actualGameId): void
    {
        $this->actualGameId = $actualGameId;
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