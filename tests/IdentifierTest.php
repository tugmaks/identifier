<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Premier\Identifier\Identifier;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class IdentifierTest extends TestCase
{
    private const UUID = '1eb65a11-b71f-67f0-baa3-7a5ffee21f49';

    public function testGenerate(): void
    {
        self::assertInstanceOf(TestId::class, TestId::generate());
    }

    public function testFromString(): void
    {
        $id = TestId::fromString(self::UUID);

        self::assertInstanceOf(TestId::class, $id);
        self::assertSame(self::UUID, $id->toString());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"bla" is not valid uuid.');

        TestId::fromString('bla');
    }

    public function testFromUuid(): void
    {
        $uuid = Uuid::fromString(self::UUID);
        $id = TestId::fromUuid($uuid);

        self::assertInstanceOf(TestId::class, $id);
        self::assertInstanceOf(UuidInterface::class, $id->toUuid());
        self::assertSame(self::UUID, $id->toString());
        self::assertSame(self::UUID, (string) $id);

        self::assertFalse($id->equal(TestId::generate()));
    }

    public function testIsValid(): void
    {
        self::assertTrue(Identifier::isValid(self::UUID));
        self::assertFalse(Identifier::isValid('bla'));
    }

    public function testFromAny(): void
    {
        self::assertInstanceOf(TestId::class, TestId::fromAny(self::UUID));
        self::assertInstanceOf(TestId::class, TestId::fromAny(Uuid::uuid6()));
        self::assertInstanceOf(TestId::class, TestId::fromAny(TestId::generate()));
    }

    public function testSame(): void
    {
        self::assertFalse(Identifier::same(TestId::generate(), TestId::generate()));

        $id = TestId::generate();
        self::assertTrue(Identifier::same($id, $id));

        self::assertTrue(Identifier::same(TestId::fromString(self::UUID), TestId::fromString(self::UUID)));
        self::assertFalse(Identifier::same(TestId::fromString(self::UUID), Test2Id::fromString(self::UUID)));
    }
}

final class TestId extends Identifier
{
}

final class Test2Id extends Identifier
{
}