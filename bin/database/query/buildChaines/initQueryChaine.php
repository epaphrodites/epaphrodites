<?php

namespace Epaphrodites\database\query\buildChaines;

trait initQueryChaine{

    /**
     * @return void
     */
    protected function initQueryChaine():void{
       
        $this->table = NULL;

        $this->param = [];

        $this->chaine = NULL;

        $this->like = NULL;

        $this->where = NULL;

        $this->group = NULL;

        $this->insert = NULL;

        $this->values = NULL;

        $this->multiChaine = [];

        $this->rset = NULL;

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

        $this->db = NULL;

        $this->rdb = NULL;
    }
}
