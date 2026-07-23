<?php

namespace Webkul\RestApi\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Guards against reintroducing framework APIs that Laravel 11/12 removed. These
 * cannot be caught by booting the package (the affected controllers depend on
 * the full Krayin CRM to autoload), so we scan the source directly. Adding one
 * back would fatal at runtime with "Trait not found" the moment the class loads.
 */
class LaravelCompatibilityTest extends TestCase
{
    /**
     * Traits that lived under Illuminate\Foundation\Auth in Laravel <= 10 and
     * were removed in Laravel 11.
     *
     * @return array<int, array{0: string}>
     */
    public static function removedFoundationAuthTraits(): array
    {
        return [
            ['Illuminate\Foundation\Auth\SendsPasswordResetEmails'],
            ['Illuminate\Foundation\Auth\ResetsPasswords'],
            ['Illuminate\Foundation\Auth\AuthenticatesUsers'],
            ['Illuminate\Foundation\Auth\RegistersUsers'],
            ['Illuminate\Foundation\Auth\ThrottlesLogins'],
            ['Illuminate\Foundation\Auth\VerifiesEmails'],
        ];
    }

    #[DataProvider('removedFoundationAuthTraits')]
    public function test_source_does_not_reference_removed_foundation_auth_trait(string $trait): void
    {
        $offenders = [];

        foreach ($this->phpSourceFiles() as $file) {
            if (str_contains(file_get_contents($file), $trait)) {
                $offenders[] = $file;
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Removed Laravel trait [$trait] is still referenced in: ".implode(', ', $offenders)
        );
    }

    /**
     * @return iterable<string>
     */
    private function phpSourceFiles(): iterable
    {
        $src = realpath(__DIR__.'/../../src');

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                yield $file->getPathname();
            }
        }
    }
}
