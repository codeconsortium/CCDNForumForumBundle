<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            //new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            //new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
			new FOS\UserBundle\FOSUserBundle(),
			new CCDNForum\ForumBundle\CCDNForumForumBundle(),
        );
    }

    /**
     * @return null
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/CCDNForumForumBundle/cache/' . $this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/CCDNForumForumBundle/logs';
    }

	/**
	 * 
	 * @link http://kriswallsmith.net/post/27979797907/get-fast-an-easy-symfony2-phpunit-optimization
	 * (does not work)
	 */
//	protected function initializeContainer()
//	{
//        static $first = true;
//
//        if ('test' !== $this->getEnvironment()) {
//            parent::initializeContainer();
//            return;
//        }
//
//        $debug = $this->debug;
//
//        if (!$first) {
//            // disable debug mode on all but the first initialization
//            $this->debug = false;
//        }
//
//        // will not work with --process-isolation
//        $first = false;
//
//        try {
//            parent::initializeContainer();
//        } catch (\Exception $e) {
//            $this->debug = $debug;
//            throw $e;
//        }
//
//        $this->debug = $debug;
//	}
}