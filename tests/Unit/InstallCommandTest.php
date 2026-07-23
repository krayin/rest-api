<?php

namespace Webkul\RestApi\Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Webkul\RestApi\Console\Commands\Install;
use Webkul\RestApi\Tests\TestCase;

/**
 * The install command shells out via exec('php artisan ...') and calls exit(),
 * so running handle() in-process is unsafe. We assert only its registration and
 * public metadata — the surface that can be verified without side effects.
 */
class InstallCommandTest extends TestCase
{
    public function test_command_is_registered_with_the_kernel(): void
    {
        $commands = $this->app[Kernel::class]->all();

        $this->assertArrayHasKey('krayin-rest-api:install', $commands);
        $this->assertInstanceOf(Install::class, $commands['krayin-rest-api:install']);
    }

    public function test_command_has_the_expected_name(): void
    {
        $command = $this->app[Kernel::class]->all()['krayin-rest-api:install'];

        $this->assertSame('krayin-rest-api:install', $command->getName());
    }

    public function test_command_exposes_a_description(): void
    {
        $command = $this->app[Kernel::class]->all()['krayin-rest-api:install'];

        $this->assertNotEmpty($command->getDescription());
    }
}
