<?php

namespace Epaphrodites\epaphrodites\shares\ajaxTemplate;

trait chatBot{
  
    /**
     * @param array $datas
     * @param string $submit
     * @param string $chatBotName
     * @return string
     */
    public function chatMessageContent(
        array $datas, 
        string $submit, 
        string $chatBotName = "EpaphroditesBot"
    ):string {
        $datas = array_reverse($datas);
        $html = '<div class="chat-container">';
        $firstItem = true;
        
        foreach ($datas as $key => $value) {
            $additionalClass = $firstItem ? 'answers' : '';
            $html .= '
            <div class="chat-item">
                <div class="msg">
                    <strong>You :</strong>
                    <p class="user-msg">' . $datas[$key]["question"] . '</p>
                </div>
                <div class="msg">
                    <strong>'.$chatBotName.' :</strong>
                    <div class="bot-msg-'.$additionalClass.'">
                        <p>' . nl2br($datas[$key]["answers"]) . '</p>
                    </div>
                </div>
            </div>';
        
            $firstItem = false;
        }
        
        $html .= '</div>';
        
        if ($submit !== '') {
            $html .= '<script>
            const delay = 10;
            
            function displayText(element, text) {
                let index = 0;
                const display = () => {
                    element.innerHTML = text.slice(0, index);
                    index++;
                    if (index <= text.length) {
                        setTimeout(display, delay);
                    }
                };
            
                display();
            }
            
            const botMessages = document.querySelectorAll(".bot-msg-answers");
            botMessages.forEach((message) => {
                displayText(message, message.innerHTML);
            });
            </script>';
        }
        
        return $html;
    }
}