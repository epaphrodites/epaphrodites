<?php 

namespace Epaphrodites\database\gearShift;

use Epaphrodites\database\gearShift\schema\makeUpGearShift as Up;
use Epaphrodites\database\gearShift\schema\makeDownGearShift as Down;
use Epaphrodites\database\query\buildQuery\buildGearShift as Build;

final class makeGearShift extends Build{

    use Down, Up;

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