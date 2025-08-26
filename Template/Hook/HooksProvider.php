<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\TagManager\Template\Hook;

use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugin\Manager;
use Piwik\Plugins\TagManager\Configuration;
use Piwik\Plugins\TagManager\SystemSettings;

class HooksProvider {

    /**
     * @var Manager
     */
    private $pluginManager;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var BaseVariable[]
     */
    private $cached;

    /**
     * @var SystemSettings
     */
    private $settings;

    public function __construct(Manager $pluginManager, Configuration $configuration, SystemSettings $systemSettings)
    {
        $this->pluginManager = $pluginManager;
        $this->configuration = $configuration;
        $this->settings = $systemSettings;
    }

    public function checkIsValidHook($hookId)
    {
        if (!$this->getHook($hookId)) {
            throw new \Exception(sprintf('The hook "%s" is not supported', $hookId));
        }
    }

    /**
     * @param string $hookId  eg "click"
     * @return BaseVariable|null
     */
    public function getHook($hookId)
    {
        foreach ($this->getAllHooks() as $hook) {
            if ($hook->getId() === $hookId) {
                return $hook;
            }
        }
    }

    /**
     * @param string $hookId  eg "click"
     * @return BaseVariable|null
     */
    public function getHookgnoreCase($hookId)
    {
        $hookId = strtolower($hookId);

        foreach ($this->getAllHooks() as $hook) {
            if (strtolower($hook->getId()) === $hookId) {
                return $hook;
            }
        }
    }

    /**
     * @return BaseVariable[]
     */
    public function getAllHooks()
    {
        if (!isset($this->cached)) {
            $hookClasses = $this->pluginManager->findMultipleComponents('Template/Hook', 'Piwik\\Plugins\\TagManager\\Template\\Hook\\BaseHook');
            
            $hooks = array();

            $restrictValue = $this->settings->restrictCustomTemplates->getValue();
            $disableCustomTemplates = $restrictValue === SystemSettings::CUSTOM_TEMPLATES_DISABLED;

            foreach ($hookClasses as $hook) {
                /** @var HookVariable $hookInstance */
                $hookInstance = StaticContainer::get($hook);

                if ($disableCustomTemplates && $hookInstance->isCustomTemplate()) {
                    continue;
                }
                $hooks[] = $hookInstance;
            }

            $this->cached = $hooks;
        }

        return $this->cached;
    }

    public function isCustomTemplate($id)
    {
        $hook = $this->getHook($id);
        if($hook) return $hook->isCustomTemplate();

        return false;
    }

}
