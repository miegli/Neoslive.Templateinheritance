<?php
namespace Neoslive\Templateinheritance\Aspects;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Repository\SiteRepository;

/**
 * @Flow\Aspect
 */
class TemplateImplentationAspect
{

    /**
     *    ==========================================================
     *    Create fallback to sites/packages templates
     *    ==========================================================
     *
     *    example configuration
     *
     *    Neoslive:
     *      Templateinheritance:
     *        Packages:
     *         'PHLU.Superglobal': TRUE
     *         'TYPO3.Neos.NodeTypes': TRUE
     *         'NEOSLIVE.IndexedNodes': TRUE
     *
     */


    /**
     * @var array
     */
    protected $settings;


    /**
     * @Flow\Inject
     * @var DomainRepository
     */
    protected $domainRepository;


    /**
     * @Flow\Inject
     * @var SiteRepository
     */
    protected $siteRepository;


    /**
     * Inject the settings
     *
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }


    /**
     * Apply (apply node context by filtering out not allowed node types)
     *
     * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
     * @Flow\Around("method(TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation->getTemplatePath())")
     * @return string
     */
    public function getTemplatePath($joinPoint)
    {


        $currentDomain = $this->domainRepository->findOneByActiveRequest();


        if ($currentDomain !== null) {
            $currentSite = $currentDomain->getSite();
        } else {
            $currentSite = $this->siteRepository->findFirstOnline();
        }

        $result = $joinPoint->getAdviceChain()->proceed($joinPoint);

        if (!isset($this->settings['Packages']) || !is_array($this->settings['Packages'])) return $result;

        $packages = $this->settings['Packages'];

        $split = explode("/", $result);

        foreach ($this->settings['Packages'] as $packageKey => $templatePackages) {

            if ($packageKey === "*" | $packageKey == $currentSite->getSiteResourcesPackageKey()) {

                foreach ($templatePackages as $key => $val) {

                    if ($val) {
                        $split[2] = $key;
                        $newresult = implode("/", $split);
                        if (is_file($newresult)) return $newresult;
                    }

                }

            }
        }


        return $result;


    }


}