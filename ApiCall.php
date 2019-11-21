<?php

/**
 * ApiCall
 * 
 * Classe responsável pelo gerenciamento genérico a chamadas a APIs
 * @author Weydans Barros
 * Data de Criação: 18/11/2019
 */

class ApiCall
{
    private static $curl;
    private static $instance = null;

    private function __construct(){}

    /**
     * apiRequest()
     * 
     * Inicializa requisição 
     * @param string $url    Endpoint que irá receber a requisição
     * @param array  $header Cabeçalhos dinamicos enviados para o end point
     */
    public static function apiRequest(string $url, array $header = null)
    {
        self::$curl = curl_init($url);

		if (!empty($header)) {
            curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $header);
        }

        if (empty(self::$instance)) {
            self::$instance = new ApiCall();
        }

        return self::$instance;
    }


    /**
     * exec()
     * 
     * Executa requisiação, recupera os dados retornados e o codigo de retorno
     * @return array $response Dados da requisição
     */
    public function exec() : array
    {
        $response['data']     = curl_exec(self::$curl);        
        $response['httpCode'] = curl_getinfo(self::$curl, CURLINFO_HTTP_CODE);
        
        curl_close(self::$curl);

        return $response;
    }


    /**
     * get()
     * 
     * 
     */
    public function get(bool $returnTransfer = true, bool $sslVerify = false)
    {   
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, $returnTransfer);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * post()
     * 
     * 
     */
    private function post(array $data, bool $sslVerify = false)
    {
        curl_setopt(self::$curl, CURLOPT_POST,           TRUE);
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * put()
     * 
     * 
     */
    private function put(array $data, bool $sslVerify = false)
    {
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'PUT');        
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * delete()
     * 
     * 
     */
    private function delete(array $data, bool $sslVerify = false)
    {
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'DELETE');   
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }

}
