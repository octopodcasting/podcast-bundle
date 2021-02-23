<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Entity;

class Episode
{
    /** @var string */
    protected $guid;

    /** @var string|null */
    protected $title;

    /** @var string|null */
    protected $link;

    /** @var string|null */
    protected $description;

    /** @var int|null */
    protected $duration;

    /** @var string|null */
    protected $author;

    /** @var string|null */
    protected $image;

    /** @var bool */
    protected $explicit = false;

    /** @var \DateTimeInterface|null */
    protected $publishedAt;

    /** @var string|null */
    protected $enclosureUrl;

    /** @var int|null */
    protected $enclosureLength;

    /** @var string|null */
    protected $enclosureType;

    /** @var string|null */
    protected $chaptersUrl;

    /** @var string|null */
    protected $chaptersType;

    /** @var string|null */
    protected $transcriptUrl;

    /** @var string|null */
    protected $transcriptType;

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;

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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

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

    public function isExplicit(): bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): self
    {
        $this->explicit = $explicit;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getEnclosureUrl(): ?string
    {
        return $this->enclosureUrl;
    }

    public function setEnclosureUrl(?string $enclosureUrl): self
    {
        $this->enclosureUrl = $enclosureUrl;

        return $this;
    }

    public function getEnclosureLength(): ?int
    {
        return $this->enclosureLength;
    }

    public function setEnclosureLength(?int $enclosureLength): self
    {
        $this->enclosureLength = $enclosureLength;

        return $this;
    }

    public function getEnclosureType(): ?string
    {
        return $this->enclosureType;
    }

    public function setEnclosureType(?string $enclosureType): self
    {
        $this->enclosureType = $enclosureType;

        return $this;
    }

    public function getChaptersUrl(): ?string
    {
        return $this->chaptersUrl;
    }

    public function setChaptersUrl(?string $chaptersUrl): self
    {
        $this->chaptersUrl = $chaptersUrl;

        return $this;
    }

    public function getChaptersType(): ?string
    {
        return $this->chaptersType;
    }

    public function setChaptersType(?string $chaptersType): self
    {
        $this->chaptersType = $chaptersType;

        return $this;
    }

    public function getTranscriptUrl(): ?string
    {
        return $this->transcriptUrl;
    }

    public function setTranscriptUrl(?string $transcriptUrl): self
    {
        $this->transcriptUrl = $transcriptUrl;

        return $this;
    }

    public function getTranscriptType(): ?string
    {
        return $this->transcriptType;
    }

    public function setTranscriptType(?string $transcriptType): self
    {
        $this->transcriptType = $transcriptType;

        return $this;
    }
}
