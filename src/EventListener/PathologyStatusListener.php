<?php

namespace App\EventListener;

use App\Constants\ColorPalette;
use App\Entity\Pathology;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::preUpdate, method: 'preUpdate')]
class PathologyStatusListener
{
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        if (!$args->getObject() instanceof Pathology) {
            return;
        }

        // If status changed, update the color accordingly
        if ($args->hasChangedField('status')) {
            $newStatus = $args->getNewValue('status');
            $newColor = ColorPalette::getColorByStatus($newStatus ?? ColorPalette::STATUS_PENDING);
            
            $args->setNewValue('color', $newColor);
        }
    }
}
