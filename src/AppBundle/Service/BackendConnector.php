<?php
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Exceptions\SecurityException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Client as GuzzleClient;

class BackendConnector
{
    /**
     * @var Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    public function __construct(
        LoggerInterface $logger,
        string $apiUrl,
        string $apiUser = null,
        string $apiPass = null
    ) {
        $this->logger = $logger;

        $baseUrl = self::sanitizeApiUrl($apiUrl);
        $this->client = new GuzzleClient([
            'base_uri' => $baseUrl,
            ''
        ]);
    }

    /**
     * Cleans a URL into a
     * @param  string $url [description]
     * @return string      [description]
     */
    private static function sanitizeApiUrl(string $url) : string
    {
        if (filter_var(
            $url,
            FILTER_VALIDATE_URL,
            FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED
        )) {
            $this->logger->warn(
                'URL validation for API base URL {url} failed',
                ['url' => $url]
            );
            throw new \RuntimeException(sprintf(
                'Backend API URL appears to be invalid.',
                $url
            ));
        }

        $urlParams = parse_url($url);
        if ($urlParams === false) {
            $this->logger->warn(
                'URL parsing of API base URL {url} failed.',
                ['url' => $url]
            );
            throw new \RuntimeException(sprintf(
                'Backend API URL appears to be invalid.',
                $url
            ));
        }

        $proto  = $urlParams['scheme'] ? "{$urlParams['scheme']}://" : null;
        $host   = $urlParams['host'] ?? null;
        $port   = $urlParams['port'] ? ":{$urlParams['port']}" : null;
        $path   = $urlParams['path'] ?? '';

        if ($proto === null || $host === null) {
            $this->logger->warn(
                'URL parsing of API is missing scheme or hostname.',
                ['url' => $url, 'params' => $urlParams]
            );
            throw new \RuntimeException(sprintf(
                'Backend API URL "%s" does not contain schema or host',
                $url
            ));
        }

        if ($proto !== 'https://') {
            $this->logger->warn(
                'API base path {url} is not connecting over HTTPS.',
                ['url' => $url, 'params' => $urlParams]
            );
            throw new SecurityException(sprintf(
                'Backend API URL "%s" uses an insecure or unsupported protocol.',
                $url
            ));
        }

        $path = '/' . trim($path, '/\\');
        return implode('', [$scheme, $host, $port, $path]);
    }

    private function getConfig()
    {
        // TODO
    }

    public function getAllUsers()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}
