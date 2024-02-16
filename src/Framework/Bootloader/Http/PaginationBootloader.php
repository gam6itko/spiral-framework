<?php

declare(strict_types=1);

namespace Spiral\Bootloader\Http;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\BinderInterface;
use Spiral\Core\Config\DeprecationProxy;
use Spiral\Framework\Spiral;
use Spiral\Http\PaginationFactory;
use Spiral\Pagination\PaginationProviderInterface;

final class PaginationBootloader extends Bootloader
{
    public function __construct(
        private readonly BinderInterface $binder,
    ) {
    }

    public function defineDependencies(): array
    {
        return [
            HttpBootloader::class,
        ];
    }

    public function defineSingletons(): array
    {
        $this->binder
            ->getBinder(Spiral::Http)
            ->bindSingleton(PaginationProviderInterface::class, PaginationFactory::class);

        $this->binder->bind(
            PaginationProviderInterface::class,
            new DeprecationProxy(PaginationProviderInterface::class, true, Spiral::Http, '4.0')
        );

        return [];
    }
}
