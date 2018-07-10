## Мои замечания на изначальный вариант.

https://docs.google.com/document/d/1d0pY2Bq3bOCmaEAoEduDhQdOlorw9IdM2XkQkZraFOs/edit, задание 3.

### 1. DecoratorManager::__construct
Интерфейс оставил бы как у родителя. Сделал бы метод типа DecoratorManager::setCache(CacheItemPoolInterface $cache)

### 2. DecoratorManager::getCacheKey
Смысл делать этот метод public?

### 3. DecoratorManager::getResponse
Метод должен называться get, судя по {@inheritdoc}? 
Если нет, зачем делать вызов parent::get($input), достаточно $this->get($input).

### 4. Тут же.
Строка return $cacheItem->get(); Вернет mixed судя по документации CacheItemInterface, но иногда этот же метод возвращает array. И в докстринге написано, что возвращает array (если, мое предпложение про get верно). Лучше бы привести к array.

### 5. $this->logger->critical('Error');
$this->logger может быть не задан, если не вызывали метод DecoratorManager::setLogger.