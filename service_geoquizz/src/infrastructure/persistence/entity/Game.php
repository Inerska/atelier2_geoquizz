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
final class Game
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private int $id;

    #[Column]
    private int $serie_id;

    #[Column]
    private int $level_id;

    private array $photos;

    #[Column]
    private string $status;

    #[Column]
    private bool $isPublic;

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos): void
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
     * @return int
     */
    public function getPhotoId(): int
    {
        return $this->photo_id;
    }

    /**
     * @param int $photo_id
     */
    public function setPhotoId(int $photo_id): void
    {
        $this->photo_id = $photo_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

}