<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\TagManager\Template\Hook;

use Piwik\Plugins\TagManager\Context\WebContext;
use Piwik\Plugins\TagManager\Template\BaseTemplate;

/**
 * @api
 */
abstract class BaseHook extends BaseTemplate
{
    CONST CATEGORY_HOOK = 'TagManager_Hooks';

    const HOOK_TARGET_BEFORE_CONTAINER_RUN = 'BeforeContainerRun';
    const HOOK_TARGET_BEFORE_TAG_FIRE = 'BeforeTagFire';

    protected $templateType = 'Hook';



    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return self::CATEGORY_HOOK;
    }

    /**
     * @inheritdoc
     */
    public function getSupportedContexts()
    {
        return array(
            WebContext::ID
        );
    }

    public function canApplyToContainerVersion($idSite, $idContainerVersion) {
      return false;
    }

    public function getTargets() {
      return [];
    }
}
