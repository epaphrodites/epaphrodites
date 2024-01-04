<?php

namespace Epaphrodites\epaphrodites\shares\ajaxTemplate;

trait messages{

    public function msgStandart($alert, $answers){
        $icon = ($alert == 'alert-success') ? '<i class="bi bi-check-square me-2"></i>' : '<i class="bi bi-exclamation-triangle me-2"></i>';
    
        return '<div class="alert ' . $alert . ' alert-dismissible fade show" role="alert" id="msgvalidation">' . $icon . $answers . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    
}