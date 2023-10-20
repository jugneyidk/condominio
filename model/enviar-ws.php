<?php
require_once('model/datos.php');
class enviarws extends Datos
{
    public function enviarws($mensaje)
    {
        include('model/api-ws.php');
        $url = 'https://api.green-api.com/waInstance' . $ID_INSTANCE . '/sendMessage/' . $API_TOKEN_INSTANCE;
        $data = array(
            'chatId' => '584244034516@c.us',
            'message' => $mensaje
        );
        $options = array(
            'http' => array(
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_encode($response);
    }
}
