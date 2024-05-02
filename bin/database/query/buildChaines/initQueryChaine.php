<?php

namespace Epaphrodites\database\query\buildChaines;

trait initQueryChaine{

    /**
     * @return void
     */
    protected function initQueryChaine():void{
       
        $this->set = NULL;

        $this->set_i = NULL;

        $this->limit = NULL;

        $this->limit_i = NULL;

        $this->rlimit = NULL;

        $this->offset = NULL;

        $this->match = NULL;

        $this->sumCase = NULL;

        $this->having = NULL;

        $this->order = NULL;
    }
}
