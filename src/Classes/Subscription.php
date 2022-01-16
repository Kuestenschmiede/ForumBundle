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

use Contao\MemberModel;

class Subscription
{
    protected $memberModel;
    protected $types = [];

    public function __construct(MemberModel $memberModel, array $types)
    {
        $this->memberModel = $memberModel;
        $this->types = $types;
    }

    public function isSubscriptionValid(string $type)
    {
        return in_array($type, $this->types);
    }

    /**
     * @return MemberModel
     */
    public function getMemberModel(): MemberModel
    {
        return $this->memberModel;
    }
}
