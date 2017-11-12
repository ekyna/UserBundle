<?php

namespace Ekyna\Bundle\UserBundle\Event;

/**
 * Class GroupEvents
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class GroupEvents
{
    // Persistence
    const INSERT      = 'ekyna_user.group.insert';
    const UPDATE      = 'ekyna_user.group.update';
    const DELETE      = 'ekyna_user.group.delete';

    // Domain
    const INITIALIZE  = 'ekyna_user.group.initialize';

    const PRE_CREATE  = 'ekyna_user.group.pre_create';
    const POST_CREATE = 'ekyna_user.group.post_create';

    const PRE_UPDATE  = 'ekyna_user.group.pre_update';
    const POST_UPDATE = 'ekyna_user.group.post_update';

    const PRE_DELETE  = 'ekyna_user.group.pre_delete';
    const POST_DELETE = 'ekyna_user.group.post_delete';


    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
