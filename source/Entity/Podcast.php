<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Entity;

class Podcast
{
    /** @var string|null */
    protected $feed;

    /** @var string|null */
    protected $title;

    /** @var string|null */
    protected $link;

    /** @var string|null */
    protected $description;

    /** @var string|null */
    protected $author;

    /** @var string|null */
    protected $image;

    /** @var array */
    protected $categories = [];

    /** @var bool */
    protected $explicit = false;

    /** @var string|null */
    protected $ownerName;

    /** @var string|null */
    protected $ownerEmail;

    public function getFeed(): ?string
    {
        return $this->feed;
    }

    public function setFeed(?string $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function addCategory(string $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    public function removeCategory(string $category): self
    {
        if (false !== $index = array_search($category, $this->categories)) {
            array_splice($this->categories, $index, 1);
        }

        return $this;
    }

    public function isExplicit(): bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): self
    {
        $this->explicit = $explicit;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(?string $ownerName): self
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->ownerEmail;
    }

    public function setOwnerEmail(?string $ownerEmail): self
    {
        $this->ownerEmail = $ownerEmail;

        return $this;
    }
}
