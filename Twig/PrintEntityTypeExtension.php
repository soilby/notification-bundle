<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.11.15
 * Time: 6.56
 */

namespace Soil\NotificationBundle\Twig;

use Soil\DiscoverBundle\Entity\TalakaProject;

class PrintEntityTypeExtension extends \Twig_Extension {

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('printEntityType', array($this, 'printEntityType'))
        ];
    }

    public function getName()
    {
        return 'print_entity_type';
    }

    public function printEntityType($entity)   {

        $namespaceDecoder = [
            'talidea' => 'Идея',
            'talblog' => 'Блог',
            'talforumtopic' => 'Форум'

        ];

        $nmspc = $entity->getRdfNamespace();

        if (array_key_exists($nmspc, $namespaceDecoder))    {
            return $namespaceDecoder[$nmspc];
        }
        else    {
            switch (true)   {
                case $entity instanceof TalakaProject:
                    return 'Проект';

                default:
                    return '';
            }

        }

    }

} 