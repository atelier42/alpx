<?php

class WebserviceRequest extends WebserviceRequestCore
{
    public static function getResources()
    {
        $resources = parent::getResources();

        $resources['gbproducts'] = [
            'description' => 'API personnalisée HappyColis synchronisation des produits',
            'specific_management' => true,
        ];

        $resources['gborders'] = [
            'description' => 'API personnalisée HappyColis synchronisation des commandes',
            'specific_management' => true,
        ];

        $resources['gbsuppliers'] = [
            'description' => 'API personnalisée HappyColis synchronisation des fournisseurs',
            'specific_management' => true,
        ];

        ksort($resources);
        return $resources;
    }
}
