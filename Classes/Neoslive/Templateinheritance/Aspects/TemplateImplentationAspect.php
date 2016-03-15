<?php
namespace Neoslive\Templateinheritance\Aspects;

use TYPO3\Flow\Annotations as Flow;


/**
 * @Flow\Aspect
 */
class TemplateImplentationAspect {

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
     * Inject the settings
     *
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }



    /**
     * Apply (apply node context by filtering out not allowed node types)
     *
     * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
     * @Flow\Around("method(TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation->getTemplatePath())")
     * @return string
     */
    public function getTemplatePath($joinPoint) {



        $result = $joinPoint->getAdviceChain()->proceed($joinPoint);

        if (!isset($this->settings['Packages']) || !is_array($this->settings['Packages'])) return $result;

        if (is_file($result)) return $result;

        $packages = $this->settings['Packages'];

        $split = explode("/",$result);

        foreach ($packages as $key => $val) {
            if ($val) {
                $split[2] = $key;
                $newresult = implode("/", $split);
                if (is_file($newresult)) return $newresult;
            }
        }


        return $result;


    }



}