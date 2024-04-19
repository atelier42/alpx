<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit32ab8afcb99d33d36c0cb685e386bc17
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PrestaShop\\Module\\FacetedSearch\\Tests\\' => 38,
            'PrestaShop\\Module\\FacetedSearch\\Controller\\' => 43,
            'PrestaShop\\Module\\FacetedSearch\\' => 32,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PrestaShop\\Module\\FacetedSearch\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests/php/FacetedSearch',
        ),
        'PrestaShop\\Module\\FacetedSearch\\Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Controller',
        ),
        'PrestaShop\\Module\\FacetedSearch\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Doctrine\\Common\\Collections\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/collections/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Doctrine\\Common\\Collections\\AbstractLazyCollection' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/AbstractLazyCollection.php',
        'Doctrine\\Common\\Collections\\ArrayCollection' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/ArrayCollection.php',
        'Doctrine\\Common\\Collections\\Collection' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Collection.php',
        'Doctrine\\Common\\Collections\\Criteria' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Criteria.php',
        'Doctrine\\Common\\Collections\\Expr\\ClosureExpressionVisitor' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/ClosureExpressionVisitor.php',
        'Doctrine\\Common\\Collections\\Expr\\Comparison' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/Comparison.php',
        'Doctrine\\Common\\Collections\\Expr\\CompositeExpression' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/CompositeExpression.php',
        'Doctrine\\Common\\Collections\\Expr\\Expression' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/Expression.php',
        'Doctrine\\Common\\Collections\\Expr\\ExpressionVisitor' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/ExpressionVisitor.php',
        'Doctrine\\Common\\Collections\\Expr\\Value' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Expr/Value.php',
        'Doctrine\\Common\\Collections\\ExpressionBuilder' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/ExpressionBuilder.php',
        'Doctrine\\Common\\Collections\\Selectable' => __DIR__ . '/..' . '/doctrine/collections/lib/Doctrine/Common/Collections/Selectable.php',
        'PrestaShop\\Module\\FacetedSearch\\Adapter\\AbstractAdapter' => __DIR__ . '/../..' . '/src/Adapter/AbstractAdapter.php',
        'PrestaShop\\Module\\FacetedSearch\\Adapter\\InterfaceAdapter' => __DIR__ . '/../..' . '/src/Adapter/InterfaceAdapter.php',
        'PrestaShop\\Module\\FacetedSearch\\Adapter\\MySQL' => __DIR__ . '/../..' . '/src/Adapter/MySQL.php',
        'PrestaShop\\Module\\FacetedSearch\\Constraint\\UrlSegment' => __DIR__ . '/../..' . '/src/Constraint/UrlSegment.php',
        'PrestaShop\\Module\\FacetedSearch\\Constraint\\UrlSegmentValidator' => __DIR__ . '/../..' . '/src/Constraint/UrlSegmentValidator.php',
        'PrestaShop\\Module\\FacetedSearch\\Definition\\Availability' => __DIR__ . '/../..' . '/src/Definition/Availability.php',
        'PrestaShop\\Module\\FacetedSearch\\Filters\\Block' => __DIR__ . '/../..' . '/src/Filters/Block.php',
        'PrestaShop\\Module\\FacetedSearch\\Filters\\Converter' => __DIR__ . '/../..' . '/src/Filters/Converter.php',
        'PrestaShop\\Module\\FacetedSearch\\Filters\\DataAccessor' => __DIR__ . '/../..' . '/src/Filters/DataAccessor.php',
        'PrestaShop\\Module\\FacetedSearch\\Filters\\Products' => __DIR__ . '/../..' . '/src/Filters/Products.php',
        'PrestaShop\\Module\\FacetedSearch\\Filters\\Provider' => __DIR__ . '/../..' . '/src/Filters/Provider.php',
        'PrestaShop\\Module\\FacetedSearch\\Form\\FeatureValue\\FormDataProvider' => __DIR__ . '/../..' . '/src/Form/FeatureValue/FormDataProvider.php',
        'PrestaShop\\Module\\FacetedSearch\\Form\\FeatureValue\\FormModifier' => __DIR__ . '/../..' . '/src/Form/FeatureValue/FormModifier.php',
        'PrestaShop\\Module\\FacetedSearch\\Form\\Feature\\FormDataProvider' => __DIR__ . '/../..' . '/src/Form/Feature/FormDataProvider.php',
        'PrestaShop\\Module\\FacetedSearch\\Form\\Feature\\FormModifier' => __DIR__ . '/../..' . '/src/Form/Feature/FormModifier.php',
        'PrestaShop\\Module\\FacetedSearch\\HookDispatcher' => __DIR__ . '/../..' . '/src/HookDispatcher.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\AbstractHook' => __DIR__ . '/../..' . '/src/Hook/AbstractHook.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Attribute' => __DIR__ . '/../..' . '/src/Hook/Attribute.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\AttributeGroup' => __DIR__ . '/../..' . '/src/Hook/AttributeGroup.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Category' => __DIR__ . '/../..' . '/src/Hook/Category.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Configuration' => __DIR__ . '/../..' . '/src/Hook/Configuration.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Design' => __DIR__ . '/../..' . '/src/Hook/Design.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Feature' => __DIR__ . '/../..' . '/src/Hook/Feature.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\FeatureValue' => __DIR__ . '/../..' . '/src/Hook/FeatureValue.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\Product' => __DIR__ . '/../..' . '/src/Hook/Product.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\ProductSearch' => __DIR__ . '/../..' . '/src/Hook/ProductSearch.php',
        'PrestaShop\\Module\\FacetedSearch\\Hook\\SpecificPrice' => __DIR__ . '/../..' . '/src/Hook/SpecificPrice.php',
        'PrestaShop\\Module\\FacetedSearch\\Product\\CoreSearchBackport' => __DIR__ . '/../..' . '/src/Product/CoreSearchBackport.php',
        'PrestaShop\\Module\\FacetedSearch\\Product\\Search' => __DIR__ . '/../..' . '/src/Product/Search.php',
        'PrestaShop\\Module\\FacetedSearch\\Product\\SearchFactory' => __DIR__ . '/../..' . '/src/Product/SearchFactory.php',
        'PrestaShop\\Module\\FacetedSearch\\Product\\SearchProvider' => __DIR__ . '/../..' . '/src/Product/SearchProvider.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\Adapter\\MySQLTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/Adapter/MySQLTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\Filters\\BlockTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/Filters/BlockTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\Filters\\ConverterTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/Filters/ConverterTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\HookDispatcherTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/HookDispatcherTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\Product\\SearchProviderTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/Product/SearchProviderTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\Product\\SearchTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/Product/SearchTest.php',
        'PrestaShop\\Module\\FacetedSearch\\Tests\\URLSerializerTest' => __DIR__ . '/../..' . '/tests/php/FacetedSearch/URLSerializerTest.php',
        'PrestaShop\\Module\\FacetedSearch\\URLSerializer' => __DIR__ . '/../..' . '/src/URLSerializer.php',
        'Ps_Facetedsearch' => __DIR__ . '/../..' . '/ps_facetedsearch.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit32ab8afcb99d33d36c0cb685e386bc17::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit32ab8afcb99d33d36c0cb685e386bc17::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit32ab8afcb99d33d36c0cb685e386bc17::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit32ab8afcb99d33d36c0cb685e386bc17::$classMap;

        }, null, ClassLoader::class);
    }
}
