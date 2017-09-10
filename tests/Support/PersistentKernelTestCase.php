<?php

namespace Tests\Support;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Provides a Kernel that's rather persistent.
 *
 * @author Roelof Roos <github@roelof.io>
 */
abstract class PersistentKernelTestCase extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function bootKernel(array $options = array())
    {
        if (static::$kernel !== null) {
            return static::$kernel;
        }

        return parent::bootKernel($options);
    }
}
