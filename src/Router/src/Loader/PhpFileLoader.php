<?php

declare(strict_types=1);

namespace Spiral\Router\Loader;

use Spiral\Core\ResolverInterface;
use Spiral\Router\Exception\LoaderLoadException;

final class PhpFileLoader implements LoaderInterface
{
    public function __construct(
        private readonly ResolverInterface $resolver
    ) {
    }

    /**
     * Loads a PHP file.
     */
    public function load(mixed $file, string $type = null): mixed
    {
        if (!\file_exists($file)) {
            throw new LoaderLoadException('File [%s] does not exist.');
        }

        // the closure forbids access to the private scope in the included file
        $load = static function (string $path) {
            return include $path;
        };

        $callback = $load($file);

        $args = $this->resolver->resolveArguments(new \ReflectionFunction($callback), validate: true);

        $callback(...$args);

        return null;
    }

    public function supports(mixed $resource, string $type = null): bool
    {
        return
            \is_string($resource) &&
            \pathinfo($resource, \PATHINFO_EXTENSION) === 'php' &&
            (!$type || $type === 'php');
    }
}
