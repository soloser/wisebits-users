<?php
declare(strict_types = 1);

namespace WiseUsers\Test\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use WiseUsers\Entities\User;

class UserTest extends TestCase
{
    private User $user;

    private array $userData = [
        'id' => 123,
        'name' => 'Oleg',
        'email' => 'oleg@gmail.com',
        'notes' => 'example note',
        'created' => '2007-01-01 10:21:32',
        'deleted' => '2012-01-01 10:21:32',
    ];

    protected function setUp(): void
    {
        $created = new DateTime($this->userData['created']);
        $deleted = new DateTime($this->userData['deleted']);
        $this->user = new User(
            $this->userData['id'],
            $this->userData['name'],
            $this->userData['email'],
            $this->userData['notes'],
            $created,
            $deleted
        );
    }

    public function testConstructor(): void
    {
        $created = new DateTime($this->userData['created']);
        $deleted = new DateTime($this->userData['deleted']);
        $user = new User(
            $this->userData['id'],
            $this->userData['name'],
            $this->userData['email'],
            $this->userData['notes'],
            $created,
            $deleted
        );
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->userData['id'], $user->getId());
        $this->assertEquals($this->userData['name'], $user->getName());
        $this->assertEquals($this->userData['email'], $user->getEmail());
        $this->assertEquals($this->userData['notes'], $user->getNotes());
        $this->assertSame($created, $user->getCreated());
        $this->assertSame($deleted, $user->getDeleted());
    }

    public function testGetName(): void
    {
        $name = 'Ivan Petrov';
        $this->user->setName($name);
        $this->assertEquals($name, $this->user->getName());
    }

    public function testGetEmail(): void
    {
        $email = 'ivan@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    public function testGetNotes(): void
    {
        $notes = 'Another notes';
        $this->user->setNotes($notes);
        $this->assertEquals($notes, $this->user->getNotes());
    }

    public function testGetId(): void
    {
        $this->assertEquals($this->userData['id'], $this->user->getId());
    }

    public function testGetCreated(): void
    {
        $this->assertEquals(new DateTime($this->userData['created']), $this->user->getCreated());
    }

    public function testGetDeleted(): void
    {
        $this->assertEquals(new DateTime($this->userData['deleted']), $this->user->getDeleted());
    }
}