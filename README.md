# Validator

## Example

Simple value

```php
$validator = new Validator(
    new RuleCollection(
        new NotBlank(),
        new Boolean(1, 0)
    )
);

// validate
$violations = $validator->validate(1);

// check status
if ($violations->hasViolations()) {
    // print violation
    $printf = new ViolationPrintf($violations);
    
    /** @var string $error */
    $error = $printf->getFirstViolation();
}
```

Object properties
```php
$validator = new Validator([
    'id' => new RuleCollection(
        new NotBlank(),
        new Type(Type::TYPE_INT)
    ),
    'name' => new RuleCollection(
        new NotBlank(),
        new Length(5,255)
    ),
]);

// validate
$violations = $validator->validate(new Entity(1, 'test'));

// check status
if ($violations->hasViolations()) {
    // print violation
    $printf = new ViolationPrintf($violations);
    
    /** @var string[] $errors */
    $errors = $printf->formatByAttributeName('name')->toArray();
}
```

Array validate
```php
$validator = new Validator([
    'id' => new RuleCollection(
        new NotBlank(),
        new Type(Type::TYPE_INT)
    ),
    'name' => new RuleCollection(
        new NotBlank(),
        new Length(5,255)
    ),
]);

// validate
$violations = $validator->validate([
    'id' => 100,
    'name' => 'test' 
]);

// check status
if ($violations->hasViolations()) {
    // print violation
    $printf = new ViolationPrintf($violations);
    
    /** @var string $error */
    $error = $printf->formatByAttributeName('name')->getFirstViolation();
}
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa php -d pcov.enabled=1 /tools/phpunit --coverage-clover=coverage.clover --colors=always
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

local
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa /tools/infection run --initial-tests-php-options='-dpcov.enabled=1'
```

or
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa ./vendor/bin/roave-infection-static-analysis-plugin run --initial-tests-php-options='-dpcov.enabled=1'
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```
