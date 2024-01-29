<?php
    
use Epaphrodites\database\query\buildQuery\buildGearShift;
        
class create_users_account_table extends buildGearShift
{
    /**
     * Run
    */ 
    public function up()
    {
        return $this->createTable('users_account', function ($table) {

            $table->addColumn('idusers_account', 'INTEGER', ['PRIMARY KEY']);
            $table->addColumn('name', 'VARCHAR(100)');
            $table->addColumn('surname', 'VARCHAR(100)');
            $table->db();
        });
    }  
    
    /**
     * Drop
    */     
    public function down(){
        
        return $this->dropTable('users_account', function ($table) {
            $table->dropColumn('name');
            $table->db();
        });
    }      
}