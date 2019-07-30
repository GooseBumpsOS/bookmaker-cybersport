<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForecastRepository")
 */
class Forecast
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $team_1;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $team_2;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $game;

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game): void
    {
        $this->game = $game;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTeam1(): ?string
    {
        return $this->team_1;
    }

    public function setTeam1(string $team_1): self
    {
        $this->team_1 = $team_1;

        return $this;
    }

    public function getTeam2(): ?string
    {
        return $this->team_2;
    }

    public function setTeam2(string $team_2): self
    {
        $this->team_2 = $team_2;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }
}
