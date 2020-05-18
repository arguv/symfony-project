<?php

namespace Website\UsersBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebsiteUsersBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
