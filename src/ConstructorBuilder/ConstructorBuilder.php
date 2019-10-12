<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

use ObjectMother\BuildStrategy;

//todo type checks based on reflected params?
//todo: make more generic MethodBuilder - this would allow easy creation
//  of factory method based builder
final class ConstructorBuilder implements BuildStrategy
{
    /** @var ParamResolver[] */
    private static $paramResolvers;

    /** @var \ReflectionClass */
    private $class;
    /** @var \ReflectionParameter[] */
    private $reflectedParams = [];
    /** @var array */
    private $overwrittenParams = [];

    /**
     * @param string $className
     * @throws \ReflectionException
     */
    public function __construct(string $className)
    {
        $this->class = new \ReflectionClass($className);
        $constructor = $this->class->getConstructor();
        if ($constructor !== null) {
            $constructor->setAccessible(true);
            foreach ($constructor->getParameters() as $parameter) {
                $this->reflectedParams[$parameter->getName()] = $parameter;
            }
        }

        if (empty(self::$paramResolvers)) {
            // Set default value resolvers
            self::$paramResolvers = [
                new DefaultValue,
                new Builtin,
                new BaseValueObject,
            ];
        }
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException
     * @throws \TypeError
     */
    public function set(string $name, ...$value): void
    {
        if (!isset($this->reflectedParams[$name])) {
            throw new \BadMethodCallException("Unknown parameter: {$name}");
        }

        $value = $this->reflectedParams[$name]->isVariadic()
            ? $value
            : reset($value);
        $this->overwrittenParams[$name] = $value;
    }

    public function unset(string $name): void
    {
        unset($this->overwrittenParams[$name]);
    }

    /**
     * @return object
     * @throws \BadMethodCallException
     */
    public function build(): object
    {
        $constructor = $this->class->getConstructor();

        // If constructor is not accessible, try to apply a workaround
        if ($constructor !== null && !$constructor->isPublic()) {
            $class = $this->class->newInstanceWithoutConstructor();

            $constructor->setAccessible(true);
            $constructor->invoke($class, ...$this->resolveParams());

            return $class;
        }

        return $this->class->newInstance(
            ...$this->resolveParams()
        );
    }

    private function resolveParams(): array
    {
        $result = [];
        $params = array_merge(
            $this->reflectedParams,
            $this->overwrittenParams
        );

        foreach ($params as $name => $param) {
            // If this was overwritten, just use it
            if (!is_object($param) || !$param instanceof \ReflectionParameter) {
                $result[] = $param;
                continue;
            }

            foreach (self::$paramResolvers as $resolver) {
                if ($resolver->canResolve($param)) {
                    $result[] = $resolver->resolve($param);
                    continue 2;
                }
            }
            throw new \BadMethodCallException("Cannot initialize parameter {$name}");
        }

        // Check last argument and process it accordingly if it is variadic
        if (is_array(end($result)) && end($this->reflectedParams)->isVariadic()) {
            $value = array_pop($result);
            $result = array_merge($result, array_values($value));
        }

        return $result;
    }

    public static function registerResolver(ParamResolver $resolver): void
    {
        self::$paramResolvers[] = $resolver;
    }

    public function getCoveredClass(): string
    {
        return $this->class->getName();
    }
}