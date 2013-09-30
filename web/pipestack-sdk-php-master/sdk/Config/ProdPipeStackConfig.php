<?php

require_once 'AbstractPipeStackConfig.php';

class ProdPipeStackConfig extends AbstractPipeStackConfig {

    protected $clientId = '52461a1d18a51639178b4568';

    protected $clientSecret = '59b7289ac0';

    protected $accessToken = 'tA5DOzzn6J-VZuE7qwWJqcnieHVhAvd_VJ22DqhOqnlS3lRSOd2pLLv1edIxQTEHSM3bia0brHpNHMtujGwzP_yIDP5fgYxSgkTAJ_vJBSOpIJxsMTKQrnK2zIiDA3L7';

    protected $protocol = 'http://';

    protected $format = 'json';

    protected $hostname = 'api.pipestack.com';

    protected $timeout = 20;

}