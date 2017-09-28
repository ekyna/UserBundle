<?php

namespace Ekyna\Bundle\UserBundle\Event;

/**
 * Class UserEvents
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class UserEvents
{
    // Persistence
    const INSERT      = 'ekyna_user.user.insert';
    const UPDATE      = 'ekyna_user.user.update';
    const DELETE      = 'ekyna_user.user.delete';

    // Domain
    const INITIALIZE  = 'ekyna_user.user.initialize';

    const PRE_CREATE  = 'ekyna_user.user.pre_create';
    const POST_CREATE = 'ekyna_user.user.post_create';

    const PRE_UPDATE  = 'ekyna_user.user.pre_update';
    const POST_UPDATE = 'ekyna_user.user.post_update';

    const PRE_DELETE  = 'ekyna_user.user.pre_delete';
    const POST_DELETE = 'ekyna_user.user.post_delete';
}
