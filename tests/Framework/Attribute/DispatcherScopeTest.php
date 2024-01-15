<?php

declare(strict_types=1);

namespace Spiral\Tests\Attribute;

use PHPUnit\Framework\Attributes\DataProvider;
use Spiral\App\Dispatcher\DispatcherWithCustomEnum;
use Spiral\App\Dispatcher\DispatcherWithScopeName;
use Spiral\App\Dispatcher\DispatcherWithStringScope;
use Spiral\App\Dispatcher\Scope;
use Spiral\Boot\AbstractKernel;
use Spiral\Core\Container;
use Spiral\Framework\ScopeName;
use Spiral\Tests\Framework\BaseTestCase;

final class DispatcherScopeTest extends BaseTestCase
{
    #[DataProvider('dispatchersDataProvider')]
    public function testDispatcherScope(string $dispatcher, string|\BackedEnum $scope): void
    {
        $this->beforeBooting(function (AbstractKernel $kernel, Container $container) use ($dispatcher, $scope): void {
            $kernel->addDispatcher($container->get($dispatcher));
            $container->getBinder($scope)->bind('foo', new \stdClass());
        });

        $app = $this->makeApp();

        $this->assertInstanceOf(\stdClass::class, $app->serve());
    }

    public static function dispatchersDataProvider(): \Traversable
    {
        yield [DispatcherWithScopeName::class, ScopeName::Console];
        yield [DispatcherWithCustomEnum::class, Scope::Custom];
        yield [DispatcherWithStringScope::class, 'test'];
    }
}
