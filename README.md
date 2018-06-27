# Koncept/DI

Call maps from `type: string` into `object` `TypeMap`.  By using `TypeMap`, various utility, especially dependency injection become possible.

# Installation

`composer require koncept/dependency-injection`

# Classes

## Type Map

```php
<?php
namespace Koncept\DI;

interface TypeMapInterface
{
    /**
     * Acquire object of the type.
     *
     * @param string $type
     * @return object
     */
    public function get(string $type): object;

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool;
}

interface FiniteTypeMapInterface
    extends TypeMapInterface
{
    /**
     * Return the list of supported types.
     *
     * @return Vector_string
     */
    public function getList(): Vector_string;
}
```

The list of classes which can be provided by `Finite Type Map` is finite. In contrast, `Type Map` accept various classes and the list of classes which can be provided by `Type Map` may be infinite.

## Object Container (`\Koncept\DI\Utility\ObjectContainer` implements `FiniteTypeMapInterface`)

```php
<?php

class MyClassA {}
class MyClassB {}
class MyClassC {}
class MyClassD extends MyClassC {}

$objectA = new MyClassA;
$objectB = new MyClassB;
$objectD = new MyClassD;

$typeMap = (new ObjectContainer($objectA, $objectB))->with($objectD, MyClassC::class);
```

`ObjectContainer`s can be merged by `ObjectContainer::Merge()`.

## Argument Resolver (`\Koncept\DI\Utility\ArgumentResolver`)

```php
<?php

class MyClassA {}
class MyClassB {}
class MyClassC {}
class MyClassD {}

$resolver = new ArgumentResolver(new ObjectContainer(new MyClassA, new MyClassB, new MyClassC, new MyClassD));

$closureToSolve = function(MyClassA $x, MyClassB $y, MyClassD $z) { return 'success!'; };

$arguments = $resolver->resolveClosure($closureToSolve);
$arguments[0] instanceof MyClassA;  // true
$arguments[1] instanceof MyClassB;  // true
$arguments[2] instanceof MyClassD;  // true

$closureToSolve(...$arguments);     // 'success'
```

## Factory (`\Koncept\DI\Utility\Factory` implements `TypeMapInterface`)

```php
<?php

class MyClassA {}
class MyClassB {}
class MyClassC { public function __construct(MyClassA $a, MyClassB $b) {} }

$factory = new Factory(new ObjectContainer(new MyClassA, new MyClassB));
$c = $factory->get(MyClassC::class);    // Resolve arguments with ArgumentResolver and instantiate
$c instanceof MyClassC; // true
```

See also. `FiniteFactory` implements `FiniteTypeMapInterface`.

## DI Container (`\Koncept\DI\Utility\Container` implements `FiniteTypeMapInterface`)

```php
<?php

class MyClassA {}
class MyClassB {}
class MyClassC { public function __construct(MyClassA $a) {} }

class MyContainer extends Container
{
    private function provideA(): MyClassA { return new MyClassA; }
    protected function createB(): MyClassB { return new MyClassB; }
    public function provideC(MyClassA $a): MyClassC { return new MyClassC($a); }
}

$ctn = new MyContainer;

$a1 = $ctn->get(MyClassA::class);
$a2 = $ctn->get(MyClassA::class);
$a1 === $a2;    // Same instance (singleton)

$b1 = $ctn->get(MyClassB::class);
$b2 = $ctn->get(MyClassB::class);
$b1 !== $b2;    // Different instance (instanciate for each requirement)

$c = $ctn->get(MyClassC::class);
```

Provider method must begin with `provide` and must have return value type hint. Factory method must begin with `create` and must have return value type hint.

Use argument to clarify dependency. DO NOT USE another factory/provider method in a factory/provider method.

## Aggregate (`\Koncept\DI\Utility\AggregateTypeMap` implements `TypeMapInterface`)

```php
<?php

$typeMapA = new ObjectContainer(new MyClassA, new MyClassB);
$typeMapB = new class extends Container {
    protected function provideC(): MyClassC { /* ... */ }
    protected function createD(): MyClassD { /* ... */ }
};

$newTypeMap = new AggregateTypeMap($typeMapA, $typeMapB);
```
