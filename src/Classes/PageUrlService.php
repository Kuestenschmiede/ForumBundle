<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Classes;

use con4gis\CoreBundle\Classes\C4GUtils;
use Contao\Config;
use Contao\Environment;
use Contao\Input;

class PageUrlService
{
    private $alias;
    private $pageUrl;
    private $basePageUrl;

    public function __construct()
    {
        if (!isset($_GET['item']) && \Contao\Config::get('useAutoItem') && isset($_GET['auto_item'])) {
            \Contao\Input::setGet('item', \Contao\Input::get('auto_item'));
        }
        $this->alias = \Contao\Input::get('item') ? urlencode(\Contao\Input::get('item')) : '';
        $request = \Contao\System::getContainer()->get('request_stack')->getCurrentRequest();
        $this->pageUrl = ($request ? $request->getSchemeAndHttpHost() . $request->getBasePath() . $request->getRequestUri() : '');
        if ($this->alias !== '') {
            $this->basePageUrl = str_replace('/' . $this->alias, '', $this->pageUrl);
        } else {
            $this->basePageUrl = $this->pageUrl;
        }
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getPageUrl(): string
    {
        return $this->pageUrl;
    }

    /**
     * @return string
     */
    public function getBasePageUrl(): string
    {
        return $this->basePageUrl;
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getPageUrlForAlias(string $alias): string
    {
        if ($this->alias !== '') {
            return str_replace('/' . $this->alias, '/' . $alias, $this->pageUrl);
        } elseif (\con4gis\CoreBundle\Classes\C4GUtils::endsWith($this->pageUrl, '.html')) {
            return str_replace('.html', "/$alias.html", $this->pageUrl);
        }

        return $this->pageUrl . "/$alias";
    }
}
