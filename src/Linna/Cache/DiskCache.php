<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

use DateInterval;
use DateTime;
use Linna\Cache\Exception\InvalidArgumentException;
use Linna\Shared\ClassOptionsTrait;
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Disk Cache.
 *
 * Before use it, is possible configure ramdisk, work only on linux:
 * - mkdir /tmp/linna-cache
 * - sudo mount -t tmpfs -o size=128m tmpfs /tmp/linna-cache
 *
 * For check Ram Disk status
 * - df -h /tmp/linna-cache
 *
 * Serialize option is required when is needed store a class instance.
 * If you not utilize serialize, must declare __set_state() method inside
 * class or get from cache fail.
 */
class DiskCache implements CacheInterface
{
    use ActionMultipleTrait;
    use ClassOptionsTrait;

    /**
     * @var array Config options for class
     */
    protected $options = [
        'dir'       => '/tmp',
        //'serialize' => false,
        'ttl'       => 0,
    ];

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        if ($this->doesFileChecksFailed($file)) {
            return $default;
        }

        $cacheValue = include $file;

        return unserialize($cacheValue['value']);
    }

    /**
     * Checks for cache file
     * 
     * @param string $file
     * @return bool
     */
    private function doesFileChecksFailed(string $file) : bool
    {
        //check if file exist
        if (!file_exists($file)) {
            return true;
        }

        //take cache from file
        $cacheValue = include $file;

        //check if cache is expired and delete file from storage
        if ($cacheValue['expires'] <= time() && $cacheValue['expires'] !== 0) {
            unlink($file);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create cache array
        $cache = [
            'key'     => $key,
            'value'   => serialize($value),
            'expires' => $this->calculateTtl($ttl),//($ttl) ? $created + $ttl : null,
        ];

        //export
        // HHVM fails at __set_state, so just use object cast for now
        $content = str_replace('stdClass::__set_state', '(object)', var_export($cache, true));
        $content = "<?php return {$content};";

        //write file
        file_put_contents($this->options['dir'].'/'.sha1($key).'.php', $content);

        return true;
    }
    
    /**
     * Calculate ttl for cache file
     * 
     * @param null|int|DateInterval $ttl
     * @return int
     */
    private function calculateTtl($ttl) : int
    {
        if ($ttl instanceof DateInterval) {
            return (new DateTime('now'))->add($ttl)->getTimeStamp();
        }

        //check for usage of ttl default class option value
        if ($ttl === null) {
            return $this->options['ttl'];
        }
        
        return time() + $ttl;
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        //chek if file exist and delete
        if (file_exists($file)) {
            unlink($file);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        array_map('unlink', glob($this->options['dir'].'/*.php'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        if ($this->doesFileChecksFailed($file)) {
            return false;
        }

        return true;
    }
}
