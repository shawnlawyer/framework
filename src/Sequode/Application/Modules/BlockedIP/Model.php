<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {

    const Database_Connection     =   'sessions_database';

    const Table                   =	'ip_blacklist';

}