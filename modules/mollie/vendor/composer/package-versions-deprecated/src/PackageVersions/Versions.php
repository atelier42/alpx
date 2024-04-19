<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'mollie/prestashop';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'clue/stream-filter' => 'v1.6.0@d6169430c7731d8509da7aecd0af756a5747b78e',
  'composer/ca-bundle' => '1.3.7@76e46335014860eec1aa5a724799a00a2e47cc85',
  'composer/package-versions-deprecated' => '1.11.99.5@b4f54f74ef3453349c24a845d22392cd31e65f1d',
  'container-interop/container-interop' => '1.2.0@79cbf1341c22ec75643d841642dd5d6acd83bdb8',
  'guzzlehttp/promises' => '1.5.3@67ab6e18aaa14d753cc148911d273f6e6cb6721e',
  'guzzlehttp/psr7' => '1.9.1@e4490cabc77465aaee90b20cfc9a770f8c04be6b',
  'http-interop/http-factory-guzzle' => '1.1.1@6e1efa1e020bf1c47cf0f13654e8ef9efb1463b3',
  'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303',
  'league/container' => '2.5.0@8438dc47a0674e3378bcce893a0a04d79a2c22b3',
  'mollie/mollie-api-php' => 'v2.61.0@d3ec7a191985aa57bec9b4425a665e95b4ba346a',
  'php-http/client-common' => '2.7.0@880509727a447474d2a71b7d7fa5d268ddd3db4b',
  'php-http/discovery' => '1.19.1@57f3de01d32085fea20865f9b16fb0e69347c39e',
  'php-http/httplug' => '2.4.0@625ad742c360c8ac580fcc647a1541d29e257f67',
  'php-http/message' => '1.16.0@47a14338bf4ebd67d317bf1144253d7db4ab55fd',
  'php-http/message-factory' => '1.1.0@4d8778e1c7d405cbb471574821c1ff5b68cc8f57',
  'php-http/promise' => '1.1.0@4c4c1f9b7289a2ec57cde7f1e9762a5789506f88',
  'prestashop/decimal' => '1.5.0@b5afdcc4b03140f838bb7b256aec6c21fd83951b',
  'prestashop/module-lib-cache-directory-provider' => 'v1.0.0@34a577b66a7e52ae16d6f40efd1db17290bad453',
  'prestashop/module-lib-service-container' => 'v2.0@5525b56513d9ddad6e4232dfd93a24e028efdca7',
  'psr/container' => '1.1.1@8622567409010282b7aeebe4bb841fe98b58dcaf',
  'psr/http-client' => '1.0.3@bb5906edc1c324c9a05aa0873d40117941e5fa90',
  'psr/http-factory' => '1.0.2@e616d01114759c4c489f93b099585439f795fe35',
  'psr/http-message' => '1.1@cb6ce4845ce34a8ad9e68117c10ee90a29919eba',
  'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'segmentio/analytics-php' => '1.8.0@7e25b2f6094632bbfb79e33ca024d533899a2ffe',
  'sentry/sentry' => '3.17.0@95d2e932383cf684f77acff0d2a5aef5ad2f1933',
  'symfony/http-client' => 'v4.4.49@0185497cd61440bdf68df7d81241b97a543e9c3f',
  'symfony/http-client-contracts' => 'v1.1.13@59f37624a82635962f04c98f31aed122e539a89e',
  'symfony/options-resolver' => 'v4.4.44@583f56160f716dd435f1cd721fd14b548f4bb510',
  'symfony/polyfill-php73' => 'v1.28.0@fe2f306d1d9d346a7fee353d0d5012e401e984b5',
  'symfony/polyfill-php80' => 'v1.28.0@6caa57379c4aec19c0a12a38b59b26487dcfe4b5',
  'symfony/service-contracts' => 'v1.1.13@afa00c500c2d6aea6e3b2f4862355f507bc5ebb4',
  'webmozart/assert' => '1.11.0@11cb2199493b2f8a3b53e7f19068fc6aac760991',
  'mollie/prestashop' => 'dev-develop@ab197796c73e8a8dc4c3fbee89e977c78260d386',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (self::composer2ApiUsable()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }

    private static function composer2ApiUsable(): bool
    {
        if (!class_exists(InstalledVersions::class, false)) {
            return false;
        }

        if (method_exists(InstalledVersions::class, 'getAllRawData')) {
            $rawData = InstalledVersions::getAllRawData();
            if (count($rawData) === 1 && count($rawData[0]) === 0) {
                return false;
            }
        } else {
            $rawData = InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return false;
            }
        }

        return true;
    }
}
