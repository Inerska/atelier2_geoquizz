<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
class Game
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private int $id;
    #[Column]
    private int $serie_id;
    #[Column]
    private int $level_id;
    #[Column]
    private string $photos;
    #[Column]
    private bool $isPublic;


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
     * @return int
     */
    public function getSerieId(): int
    {
        return $this->serie_id;
    }

    /**
     * @param int $serie_id
     */
    public function setSerieId(int $serie_id): void
    {
        $this->serie_id = $serie_id;
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->level_id;
    }

    /**
     * @param int $level_id
     */
    public function setLevelId(int $level_id): void
    {
        $this->level_id = $level_id;
    }

    /**
     * @return string
     */
    public function getPhotos(): string
    {
        return $this->photos;
    }

    /**
     * @param string $photos
     */
    public function setPhotos(string $photos): void
    {
        $this->photos = $photos;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     */
    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }
}