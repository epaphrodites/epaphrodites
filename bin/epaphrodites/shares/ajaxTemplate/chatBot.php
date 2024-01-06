<?php

namespace Epaphrodites\epaphrodites\shares\ajaxTemplate;

trait chatBot{
  
    /**
     * @return string
     */
    public function chatMessageContent(array $datas , string $chatBotName = "EpaphroditesBot"){

        $datas = array_reverse($datas);

        $html = '<div class="chat-container">';
    
        foreach($datas as $key => $value) {
            $html .= '<div class="chat-item">
                <div class="msg">
                    <strong>You :</strong>
                    <p>' . $datas[$key]["question"] . '</p>
                </div>
                <div class="msg">
                    <strong>'.$chatBotName.' :</strong>
                    <p>' . $datas[$key]["answers"] . '</p>
                </div>
            </div>';
        }
    
        $html .= '</div>';
    
        return $html;
    }
}