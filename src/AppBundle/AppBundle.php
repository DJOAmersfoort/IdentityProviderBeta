<?php

namespace AppBundle;

use Acelaya\Doctrine\Type\PhpEnumType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register ENUM types for Doctrine
        PhpEnumType::registerEnumTypes([
            // 'fuel_enum' => FuelType::class
        ]);
    }
}
