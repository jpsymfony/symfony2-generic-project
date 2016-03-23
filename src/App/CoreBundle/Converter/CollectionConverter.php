<?php

namespace App\CoreBundle\Converter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CollectionConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var ManagerRegistry $registry Manager registry
     */
    private $registry;
 
    /**
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        $this->registry = $registry;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * Check, if object supported by our converter
     */
    public function supports(ParamConverter $configuration)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * Applies converting
     *
     * @throws \InvalidArgumentException When route attributes are missing
     * @throws NotFoundHttpException     When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        // http://stfalcon.com/en/blog/post/symfony2-custom-paramconverter
        // http://stackoverflow.com/questions/10904759/symfony2-and-paramconverters
        $name    = $configuration->getName();
        $options = $configuration->getOptions();
        $serviceClassName = '';
        
        $max = $request->attributes->get('max', null);
        
        $orderby = "";
        if (isset($options['orderby'])
            && !empty($options['orderby'])
        ) {
            $orderby = $options['orderby'];
        }
        
        try {
            $serviceClassName = $options['manager'];
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('%s service manager option not found.', $serviceClassName));
        }

        try {
            $result = $this->container->get($serviceClassName)->all("object", $max, $orderby);
            $collection = new ArrayCollection($result);
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('%s service manager not found.', $serviceClassName));
        }
        
        if (!($collection instanceof ArrayCollection)) {
            throw new NotFoundHttpException(sprintf('%s objects not found.', $name));
        }

        $request->attributes->set($name, $collection);
    }
}
