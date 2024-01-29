<?php
    
use Epaphrodites\database\query\buildQuery\buildGearShift;
        
class drop_users_account_table extends buildGearShift
{
    
    /**
     * Drop
    */     
    public function down(){
        
        return $this->dropTable('users_account', function ($table) {
            $table->dropColumn('surname');
            $table->db();
        });
    }           
}