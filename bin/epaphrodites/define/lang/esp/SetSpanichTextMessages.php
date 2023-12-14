<?php

namespace Epaphrodites\epaphrodites\define\lang\esp;

class SetSpanichTextMessages
{
    private $AllMessages;

    public function SwithAnswers($MessageCode)
    {

        $this->AllMessages[] =
        [
            'language' => 'spanish',
            '403-title' => 'ERROR 403',
            '404-title' => 'ERROR 404',
            '419-title' => 'ERROR 419',
            '500-title' => 'ERROR 500',
            'session_name' => _SESSION_,
            'token_name' => _CRSF_TOKEN_,
            'back' => "Volver a la página de inicio",
            '403' => "¡Acceso restringido!!!",
            'author' => 'Epaphrodites Agency',
            '500' => 'Error interno del servidor',
            '404' => "¡Oops! No se encontró la página",
            'site-title' => 'INICIO | EPAPHRODITES',
            'mdpnotsame' => "Contraseña incorrecta",
            'fileempty' => "¡No se ha seleccionado ningún archivo!",
            'description' => 'epaphrodite agency',
            '419' => "¡Tu sesión ha expirado!",
            'version' => 'EPAPHRODITES V0.01 (PHP 8.2.11)',
            'error_text' => 'txt error epaphrodites',
            'denie' => "¡Procesamiento no posible!",
            'noformat' => "El formato del archivo es incorrecto",
            'login-wrong' => "Inicio de sesión o contraseña incorrectos",
            'connexion' => "Por favor, vuelve a conectar",
            'succes' => "Procesamiento completado exitosamente",
            'no-identic' => "Lo siento, las contraseñas no coinciden",
            'vide' => "Por favor, complete todos los campos correctamente!!!",
            'no-data' => "Lo siento, no hay información que coincida con tu solicitud",
            'erreur' => "Lo siento, ocurrió un problema durante el procesamiento",
            'done' => "¡Felicidades, tu registro ha sido exitoso!",
            'rightexist' => "Lo siento, este derecho ya existe para este usuario",
            'tailleauto' => "El tamaño del archivo supera el límite permitido de 500 KB",
            'send' => "¡Enhorabuena, tu mensaje ha sido enviado exitosamente!",
            'errorsending' => "Lo siento, se produjo un problema al enviar tu mensaje",
            'denie_action' => "¡Procesamiento no posible! No tienes autorización para realizar esta acción",
            'keywords' => "Epaphrodites Agency, Creación; sitio web; digital; community manager; logo; identidad visual; marketing; comunicación;",
        ];

        return $this->AllMessages[0][$MessageCode];
    }
}
