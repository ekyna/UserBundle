<?php

namespace Ekyna\Bundle\UserBundle\Event;

/**
 * Class UserEvents
 * @package Ekyna\Bundle\UserBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class UserEvents
{
    const PRE_CREATE  = 'ekyna_user.user.pre_create';
    const POST_CREATE = 'ekyna_user.user.post_create';

    const PRE_UPDATE  = 'ekyna_user.user.pre_update';
    const POST_UPDATE = 'ekyna_user.user.post_update';

    const PRE_DELETE  = 'ekyna_user.user.pre_update';
    const POST_DELETE = 'ekyna_user.user.post_delete';
}
