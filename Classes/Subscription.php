<?php


namespace con4gis\ForumBundle\Classes;


use Contao\MemberModel;

class Subscription
{
    protected $memberModel;
    protected $types = [];

    public function __construct(MemberModel $memberModel, array $types) {
        $this->memberModel = $memberModel;
        $this->types = $types;
    }

    public function isSubscriptionValid(string $type) {
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