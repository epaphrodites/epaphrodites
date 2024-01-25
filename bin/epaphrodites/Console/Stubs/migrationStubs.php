<?php

namespace Epaphrodites\epaphrodites\Console\Stubs;

class migrationStubs{

    public static function generateMigration($FilesNames, $table)
    {

        $filesPaths = _DIR_MIGRATION_."/".date('d_m_Y')."_{$FilesNames}.php";    

$stub = "<?php
    
use Epaphrodites\\database\\query\\buildQuery\\buildGearShift;
        
class $FilesNames extends buildGearShift
{
    /**
     * Run
    */ 
    public function up()
    {
        return \$this->createTable('$table', function (\$table) {

            \$table->addColumn('id$table', 'INTEGER', ['PRIMARY KEY']);
            \$table->addColumn('name', 'VARCHAR(255)');
        });
    }  
    
    /**
     * Drop
    */     
    public function down(){
        
        return \$this->dropTable('$table', function (\$table) {
            \$table->dropColumn('name');
            \$table->db();
        });
    }      
}";
        
    file_put_contents( $filesPaths, $stub);
    }


    public static function dropMigration($FilesNames, $table)
    {

        $filesPaths = _DIR_MIGRATION_."/".date('d_m_Y')."_{$FilesNames}.php";    

$stub = "<?php
    
use Epaphrodites\\database\\query\\buildQuery\\buildGearShift;
        
class $FilesNames extends buildGearShift
{
    
    /**
     * Drop
    */     
    public function down(){
        
        return \$this->dropTable('$table', function (\$table) {
            \$table->dropColumn('name');
            \$table->db();
        });
    }           
}";
        
    file_put_contents( $filesPaths, $stub);
    }    
}