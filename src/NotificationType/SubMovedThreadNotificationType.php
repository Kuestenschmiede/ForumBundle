<?php

namespace con4gis\ForumBundle\NotificationType;

use Terminal42\NotificationCenterBundle\NotificationType\NotificationTypeInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\EmailTokenDefinition;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\TokenDefinitionFactoryInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\TextTokenDefinition;

class SubMovedThreadNotificationType implements NotificationTypeInterface
{
    public const NAME = 'sub_moved_thread';

    public function __construct(private TokenDefinitionFactoryInterface $factory)
    {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenDefinitions(): array
    {
        return [
            $this->factory->create(EmailTokenDefinition::class, 'admin_email', 'admin_email'),
            $this->factory->create(EmailTokenDefinition::class, 'user_email', 'user_email'),
            $this->factory->create(TextTokenDefinition::class, 'user_name', 'user_name'),
            $this->factory->create(TextTokenDefinition::class, 'threadname', 'threadname'),
            $this->factory->create(TextTokenDefinition::class, 'forumname', 'forumname'),
            $this->factory->create(TextTokenDefinition::class, 'responsible_username', 'responsible_username'),
            $this->factory->create(TextTokenDefinition::class, 'link', 'link'),
            $this->factory->create(TextTokenDefinition::class, 'unsubscribe_link', 'unsubscribe_link'),
            $this->factory->create(TextTokenDefinition::class, 'unsubscribe_all_link', 'unsubscribe_all_link'),
        ];
    }
}
