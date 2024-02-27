<?php 

namespace Epaphrodites\database\gearShift;

use Epaphrodites\database\gearShift\schema\makeUpGearShift;
use Epaphrodites\database\gearShift\schema\makeDownGearShift;
use Epaphrodites\database\query\buildQuery\buildGearShift;

final class makeGearShift extends buildGearShift{

    use makeDownGearShift, makeUpGearShift;

    /**
     * All up migration
     * @return array
    */
    public final function up():array{
        return [
            $this->createUsersAccountTable()
        ];
    }

    /**
     * All down migration
     * @return array
    */
    public final function down():array{
		return [
            $this->dropUsersAccountColumn()
        ];
    }    
}