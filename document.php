<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

$documentorPath = __DIR__ . '/phpDocumentor.phar';
$documentorKeyPath = __DIR__ . '/phpDocumentor.phar.pubkey';
$documentorDownloadLink = 'https://github.com/phpDocumentor/phpDocumentor2/releases/download/v3.0.0-alpha.3/phpDocumentor.phar';
$documentorKeyDownloadLink = 'https://github.com/phpDocumentor/phpDocumentor2/releases/download/v3.0.0-alpha.3/phpDocumentor.phar.pubkey';

if (!file_exists($documentorPath)) {
   echo "Documentor executable does not exist downloading..." . PHP_EOL;
   file_put_contents($documentorPath, file_get_contents($documentorDownloadLink));
   file_put_contents($documentorKeyPath, file_get_contents($documentorKeyDownloadLink));
}

echo "Building documentation." . PHP_EOL;
exec(PHP_BINARY . ' ' . $documentorPath . ' run -d src -t api');

echo "Done" . PHP_EOL;