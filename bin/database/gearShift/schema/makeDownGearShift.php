<?php 

namespace Epaphrodites\database\gearShift\schema;

trait makeDownGearShift{

    /**
     * Drop Column
     * create 25/01/2024
     */
    public function dropUsersAccountColumn()
    {
        return $this->dropTable('users_account', function ($table) {
            $table->dropColumn('name');
            $table->db();
        });
    }              
}