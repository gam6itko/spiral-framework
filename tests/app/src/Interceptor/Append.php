<?php

declare(strict_types=1);

namespace Spiral\App\Interceptor;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;

class Append implements CoreInterceptorInterface
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): array
    {
        $result = $core->callAction($controller, $action, $parameters);
        if (!is_array($result)) {
            $result = [];
        }
        $result[] = $this->string;
        return $result;
    }
}
