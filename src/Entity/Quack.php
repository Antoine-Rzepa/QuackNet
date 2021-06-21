<?php

namespace App\Entity;

use App\Form\DuckProfilType;
use App\Repository\QuackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

/**
 * @ORM\Entity(repositoryClass=QuackRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Quack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Duck::class, inversedBy="quacks", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="quacks")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=Quack::class, inversedBy="quacks", fetch="EAGER")
     */
    private $quack;

    /**
     * @ORM\OneToMany(targetEntity=Quack::class, mappedBy="quack", orphanRemoval=true)
     */
    private $quacks;

    /**
     * @ORM\PreRemove()
     */
    public function removeTags(){
        $this->tags->clear();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function __construct(Duck $duck)
    {
        $this->created_at = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->picture = $duck->getPicture();
        $this->quacks = new ArrayCollection();
    }

    public function getAuthor(): ?Duck
    {
        return $this->author;
    }

    public function setAuthor(?Duck $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function emptyTag(): self
    {
        $this->tags->clear();

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getQuack(): ?self
    {
        return $this->quack;
    }

    public function setQuack(?self $quack): self
    {
        $this->quack = $quack;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getQuacks(): Collection
    {
        return $this->quacks;
    }

    public function addQuack(self $quack): self
    {
        if (!$this->quacks->contains($quack)) {
            $this->quacks[] = $quack;
            $quack->setQuack($this);
        }

        return $this;
    }

    public function removeQuack(self $quack): self
    {
        if ($this->quacks->removeElement($quack)) {
            // set the owning side to null (unless already changed)
            if ($quack->getQuack() === $this) {
                $quack->setQuack(null);
            }
        }

        return $this;
    }


}
