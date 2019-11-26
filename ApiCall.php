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
     * getInstance()
     *
     * Retorna instância única seguindo o padrão Singleton
     * @return ApiCall instância da própria classe
     */   
    public static function getInstance() : ApiCall
    {
        if (empty(self::$instance)) {
            self::$instance = new ApiCall();
        }

        return self::$instance;
    }


    /**
     * apiRequest()
     * 
     * Inicializa requisição 
     * @param string $url    Endpoint que irá receber a requisição
     * @param array  $header Cabeçalhos dinamicos enviados para o end point
     */
    public static function apiRequest(string $url, array $header = null) : ApiCall
    {
        self::$curl = curl_init($url);

		if (!empty($header)) {
            curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $header);
        }

        return self::getInstance();
    }


    /**
     * exec()
     * 
     * Executa requisiação, recupera os dados retornados e o código de retorno
     * @return array $response Dados da resposta da requisição
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
     * Realiza parametrização requisições do tipo GET
     * @param  bool $returnTransfer Configura retorno como string ao invés de printar na tela
     * @param  bool $sslVerify      Verifica certificado do par 
     * @return ApiCall              Instância da p´ropria classe
     */
    public function get(bool $returnTransfer = true, bool $sslVerify = false) : ApiCall
    {   
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, $returnTransfer);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * post()
     * 
     * Realiza parametrização requisições do tipo POST
     * @param  array $data     Dados enviados na requisição
     * @param  bool $sslVerify Verifica certificado do par 
     * @return ApiCall         Instância da p´ropria classe
     */
    private function post(array $data, bool $sslVerify = false) : ApiCall
    { 
        curl_setopt(self::$curl, CURLOPT_POST,           TRUE);
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * put()
     * 
     * Realiza parametrização requisições do tipo PUT
     * @param  array $data     Dados enviados na requisição
     * @param  bool $sslVerify Verifica certificado do par 
     * @return ApiCall         Instância da p´ropria classe
     */
    private function put(array $data, bool $sslVerify = false) : ApiCall
    {
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'PUT');        
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }


    /**
     * delete()
     * 
     * Realiza parametrização requisições do tipo DELETE
     * @param  array $data     Dados enviados na requisição
     * @param  bool $sslVerify Verifica certificado do par 
     * @return ApiCall         Instância da p´ropria classe
     */
    private function delete(array $data, bool $sslVerify = false) : ApiCall
    {
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'DELETE');   
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS,     $data);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);

        return self::$instance;
    }

}
