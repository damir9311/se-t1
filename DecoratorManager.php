<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    protected $cache;
    protected $logger;

    public function setCache(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        $result = [];
        try {
            // оставляю parent::get, т.к. не знаю это намеренно или нет
            $result = parent::get($input);

            if ($this->cache) {
                $cacheKey = $this->getCacheKey($input);
                $cacheItem = $this->cache->getItem($cacheKey);
                if ($cacheItem->isHit()) {
                    $result = $cacheItem->get();
                }

                if ($result) {
                    $cacheItem
                        ->set($result)
                        ->expiresAt(
                            (new DateTime())->modify('+1 day')
                        );
                }
            }

        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return $result;
    }

    protected function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}
