<?php

namespace con4gis\ForumBundle\Classes\Callbacks;

use Contao\System;

class ModuleCallback
{
    public function getImageSizes()
    {
        return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(System::getContainer()->get('security.helper')->getUser());
    }

    public function getLanguages()
    {
        return System::getContainer()->get('contao.intl.locales')->getEnabledLocales();
    }

    public function updateSitemap($value, $dc)
    {
        if ($value != $dc->varValue) {
            // force update of sitemap in the frontend by setting last sitemap timestamp to 0
            System::getContainer()->get('database_connection')->executeStatement(
                "UPDATE tl_module SET c4g_forum_sitemap_updated=0 WHERE id = ?",
                [$dc->id]
            );
        }

        return $value;
    }
}
