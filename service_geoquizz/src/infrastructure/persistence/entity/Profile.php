<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'profiles'), Entity]
final class Profile
{
    #[Id, Column(name: 'id', type: 'integer')]
    private int $id;

    #[Column(name: 'username', type: 'string')]
    private string $username;

    #[Column(name: 'email', type: 'string')]
    private string $email;
    #[Column(name: 'firstname', type: 'string')]
    private string $firstname;
    #[Column(name: 'lastname', type: 'string')]
    private string $lastname;
    #[Column(name: 'actualGame'), OneToOne(targetEntity: 'Game')]
    private Game $actualGame;
    #[Column(name: 'savedGames'), OneToMany(targetEntity: 'PlayedGame')]
    private array $savedGames;

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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return Game
     */
    public function getActualGame(): Game
    {
        return $this->actualGame;
    }

    /**
     * @param Game $actualGame
     */
    public function setActualGame(Game $actualGame): void
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