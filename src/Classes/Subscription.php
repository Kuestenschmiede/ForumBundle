<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2026, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Classes;

use Contao\MemberModel;

class Subscription
{
    protected $memberModel;
    protected $types = [];

    public function __construct(?MemberModel $memberModel, array $types)
    {
        $this->memberModel = $memberModel;
        $this->types = $types;
    }

    public function isSubscriptionValid(string $type)
    {
        if ($this->memberModel === null) {
            return false;
        }
        return in_array($type, $this->types);
    }

    /**
     * @return MemberModel|null
     */
    public function getMemberModel(): ?MemberModel
    {
        return $this->memberModel;
    }
}
