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

use con4gis\ProjectsBundle\Classes\Notifications\C4GNotification;

class C4GForumNotification extends C4GNotification
{
    const SUB_NEW_THREAD = 'sub_new_thread';
    const SUB_DELETED_THREAD = 'sub_deleted_thread';
    const SUB_MOVED_THREAD = 'sub_moved_thread';
    const SUB_NEW_POST = 'sub_new_post';
    const SUB_DELETED_POST = 'sub_deleted_post';
    const SUB_EDITED_POST = 'sub_edited_post';
    const MAIL_NEW_PM = 'mail_new_pm';

    protected $tokens;

    /**
     * C4GForumNotification constructor.
     * @param string $type
     * @throws \Exception
     */
    public function __construct(string $type)
    {
        switch ($type) {
            case static::SUB_DELETED_THREAD:
            case static::SUB_MOVED_THREAD:
            case static::SUB_NEW_THREAD:
            case static::SUB_DELETED_POST:
            case static::SUB_EDITED_POST:
                $tokens = [
                    'tokens' => [
                        'admin_email',
                        'user_email',
                        'user_name',
                        'threadname',
                        'forumname',
                        'responsible_username',
                        'link',
                        'unsubscribe_link',
                        'unsubscribe_all_link',
                    ],
                ];

                break;
            case static::SUB_NEW_POST:
                $tokens = [
                    'tokens' => [
                        'admin_email',
                        'user_email',
                        'user_name',
                        'threadname',
                        'forumname',
                        'responsible_username',
                        'link',
                        'unsubscribe_link',
                        'unsubscribe_all_link',
                        'post_title',
                    ],
                ];

                break;
            case static::MAIL_NEW_PM:
                $tokens = [
                    'tokens' => [
                        'admin_email',
                        'user_email',
                        'user_name',
                        'responsible_username',
                        'link',
                        'message',
                        'subject',
                    ],
                ];

                break;
            default:
                $tokens = [];

                break;
        }
        parent::__construct($tokens);
    }
}
