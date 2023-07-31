<?php
declare(strict_types = 1);

namespace WiseUsers\Entities;

use DateTime;
use WiseUsers\Loggers\LoggableInterface;

class User implements LoggableInterface
{
    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private ?string $email = null,
        private ?string $notes = null,
        private ?DateTime $created = null,
        private ?DateTime $deleted = null,
    ) {}

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getDeleted(): ?DateTime
    {
        return $this->deleted;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     * @return User
     */
    public function setNotes(?string $notes): User
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Implementation example
     * @return string
     */
    public function toString(): string
    {
        return (string)$this->getId();
    }
}