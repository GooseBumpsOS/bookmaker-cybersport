<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeoRepository")
 */
class Seo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $news_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $html_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $alt;

    /**
     * @return mixed
     */
    public function getNewsName()
    {
        return $this->news_name;
    }

    /**
     * @param mixed $news_name
     */
    public function setNewsName($news_name): void
    {
        $this->news_name = $news_name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHtmlTitle(): ?string
    {
        return $this->html_title;
    }

    public function setHtmlTitle(string $html_title): self
    {
        $this->html_title = $html_title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }
}
