<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\persistence\entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Table, Entity]
final class Game
{
    #[Id, GeneratedValue(strategy: 'IDENTITY')]
    private int $id;
    private int $serie_id;
    private int $level_id;
    private int $photo_id;
    private string $status;

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