<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\TagManager\Model;

use Piwik\Plugins\TagManager\Template\Hook\HooksProvider;
use Piwik\Plugin\Manager;


class Hook extends BaseModel
{
    /**
     * @var HooksProvider
     */
    private $hooksProvider;


    public function __construct(HooksProvider $hooksProvider)
    {
        $this->hooksProvider = $hooksProvider;
    }

    public function getContainerHooks($idSite, $idContainerVersion) {
        $allHooks = $this->hooksProvider->getAllHooks();
        $hookClasses = array_filter($allHooks, fn ($hook) => $hook->canApplyToContainerVersion($idSite, $idContainerVersion));

        return array_map(fn ($hook) => [
            'hookClass' => $hook,
            'type' => $hook->getName(),
            'name' => $hook->getName(),
            'targets' => $hook->getTargets(),
        ], $hookClasses);
    }

}

