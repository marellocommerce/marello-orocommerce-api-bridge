<?php

namespace Marello\Bundle\SalesBundle\Twig;

use Marello\Bundle\SalesBundle\Entity\Repository\SalesChannelRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SalesExtension extends AbstractExtension
{
    const NAME = 'marello_sales';

    /**
     * @var SalesChannelRepository
     */
    private $salesChannelRepository;

    /**
     * @param SalesChannelRepository $salesChannelRepository
     */
    public function __construct(SalesChannelRepository $salesChannelRepository)
    {
        $this->salesChannelRepository = $salesChannelRepository;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'marello_sales_has_active_channels',
                [$this, 'checkActiveChannels']
            )
        ];
    }

    /**
     * @return boolean
     */
    public function checkActiveChannels()
    {
        if ($this->salesChannelRepository->getActiveChannels()) {
            return true;
        }
        
        return false;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::NAME;
    }
}
