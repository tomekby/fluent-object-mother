# fluent-object-mother
Library helping with creation of fluent object mothers

Library inspired by [this article](https://reflectoring.io/objectmother-fluent-builder/). Why? Oftentimes we have to 
build same objects between different test cases, and this is when [Object Mother](https://www.martinfowler.com/bliki/ObjectMother.html)
comes to use. But what about a case when we have to build _almost_ the same objects? E.g. different by one field?
What is usually happening is a dozen of methods in single object mother for creating hard to differentiate objects. Solutions?
- make every DTO mutable, possibly losing control over its state or implementing superflous setters just because tests need them
- make DTO [non-final](https://ocramius.github.io/blog/when-to-declare-classes-final/) and use stubs provided by testing framework.
But then, mocking DTOs might easily prove to be more verbose than typical initialization. Also, if DTO/Collection etc. changes
you might have to fix each mock accordingly.

## How to use it? 
Every class should extend `ObjectMother\FluentMother` abstract. This class provides base methods that should ease it's use:

- `__set($name, $value): void` and `__call($name, $value): static` for setting selected values, based on selected build strategy
- `__unset($name)` for resetting selected value back to it's default
- `build(): object` this method creates expected class
- `_initialize(): BuildStrategy` should return selected strategy used for building destination instance

### Traits
If you don't want to build mentioned build strategy, there are also some trait's providing bit more abstract functionality:

`ObjectMother\Constructor`
- defines it's own `_initialize()` method based on the result of abstract method `_class(): string`. This abstract method 
should return Fully Qualified Name of a class being built by current object mother
- defines additional method `_defaults(): array` where can be set array with default values for some parameters. This array
should be in form `['parameterName' => 'parameterValue']`

### Examples?
That's it, you are good to go. If you don't override some parameters, values for them would be based on their defined types.
Some usage examples are available inside `tests/` directory (functional tests mostly), some simple use-cases are also available
inside `UseCase` directory.

If you want, a good idea might be defining setters for magic calls in class annotations (see `UseCase/TestDTOMother.php`).
This will provide you with static analysis and code completion inside of IDE.

## Is this production ready?
I'd say no, it's more of a Proof-of-concept/on-the-way-to-MVP ;) There are some base functional tests and code seems to 
work in typical situations. But there are is no unit tests coverage and functional coverage is rather small.

## Build strategies
Every build strategy has to implement `ObjectMother\BuildStrategy`. If you use variadic parameters, you can pass them to
`__call()` method like you would to e.g. constructor (`$mother->foo(1, 2, 3)`). Setting more than single value for variadic 
arguments **is not** available through setter (i.e. `$builder->foo = ...`)

### Constructor based builder
Currently only supported strategy, builds class via it's constructor (if defined). If constructor is not public, class
still will be built, but **be careful**. This constructor is not accessible for a reason. Default values for each param
are resolved by classes implementing `ObjectMother\ConstructorBuilder\ParamResolver`. Currently supported are:
- default value - if parameter has declared default, then it'll be used
- built in types (`string = ''`, `int = 0`, `float = 0.0`, `bool = true`, `array = []`/`iterable = []`)
- base value objects, currently:  
    - `DateTime`, `DatetTimeImmutable`, `DateTimeInterface` all initiated with 0:00 today
    - `DateTimeZone` initiated with UTC timezone
    - `MyCLabs\Enum` (or rather classes extending it), initiated with first available constant

If there are no type hints for argument, or it allows null, then it will be initiated with null by default. If argument 
was overwritten with some value, it won't be auto-initiated as there's no need for it. If any argument is left uninitialized,
`\BadMethodCall` will be thrown. 

#### But what about...?
If the type you'd wished to be auto-initialized is not currently supported you can always write your own resolver. All it
has to do is implement `ObjectMother\ConstructorBuilder\ParamResolver` and be later registered (e.g. in bootstrap file 
or in test init) with `ObjectMother\ConstructorBuilder\ConstructorBuilder::registerResolver($myResolver)`

## Problems
- Values set by default are resolved based on reflection (usually), so they might be semantically valid, they still might
always work. This might happen if e.g. you have some domain-related assertions inside constructor. In this case you might 
need to overwrite these manually 
- magic/reflections which have some overhead for the running code. This shouldn't be an issue, since usually we are not
building thousands of test DTOs inside tests
- refactor/rename/etc. - for the ease of use and implementation, constructor based strategy uses same setter names as
constructor parameters. This might be a problem when someone changes constructor signature, as IDE won't reflect these
inside object mother (which might or might not be needed) 