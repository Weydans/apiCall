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
     * @return ApiCall              Instância da própria classe
     */
    public function get(bool $returnTransfer = true, bool $sslVerify = false) : ApiCall
    {   
        self::commonSetOpt(false, $returnTransfer, $sslVerify);

        return self::$instance;
    }


    /**
     * post()
     * 
     * Realiza parametrização requisições do tipo POST
     * @param  array   $data      Dados enviados na requisição
     * @param  bool    $json      Formato de envio dos dados como JSON
     * @param  bool    $sslVerify Verifica certificado do par 
     * @return ApiCall Instância da própria classe
     */
    public function post(array $data, bool $json = true, bool $returnTransfer = true, bool $sslVerify = false) : ApiCall
    { 
        $data = self::isJson($data, $json);

        curl_setopt(self::$curl, CURLOPT_POST, TRUE);

        self::commonSetOpt($data, $returnTransfer, $sslVerify);

        return self::$instance;
    }


    /**
     * put()
     * 
     * Realiza parametrização requisições do tipo PUT
     * @param  array $data     Dados enviados na requisição
     * @param  bool $sslVerify Verifica certificado do par 
     * @return ApiCall         Instância da própria classe
     */
    public function put(array $data, bool $returnTransfer = true, bool $sslVerify = false) : ApiCall
    {
        $data = self::isJson($data, $json);

        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'PUT'); 

        self::commonSetOpt($data, $returnTransfer, $sslVerify);

        return self::$instance;
    }


    /**
     * delete()
     * 
     * Realiza parametrização requisições do tipo DELETE
     * @param  array $data     Dados enviados na requisição
     * @param  bool $sslVerify Verifica certificado do par 
     * @return ApiCall         Instância da própria classe
     */
    public function delete(array $data, bool $returnTransfer = true, bool $sslVerify = false) : ApiCall
    {
        $data = self::isJson($data, $json);

        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'DELETE'); 

        self::commonSetOpt($data, $returnTransfer, $sslVerify);

        return self::$instance;
    }


    /**
     * isJson()
     *
     * Converte um array de dados para formato JSON
     * @param  array  $data Dados da requisição
     * @param  bool   $json Tipo de dado JSON
     * @return Dados no formato JSON caso parâmetro $json igual a true
     */
    private static function isJson(array $data, bool $json)
    {
        if ($json) {
            return json_encode($data);
        }

        return $data;
    }


    /**
     * commonSetOpt()
     *
     * Realiza configuração de opções da biblioteca CURL
     * @param mixed $data           Dados de envio no formato correto ( array ou JSON )
     * @param bool  $returnTransfer Configura retorno como string ao invés de printar na tela
     * @param bool  $sslVerify      Verifica certificado do par
     */
    private static function commonSetOpt($data, bool $returnTransfer, bool $sslVerify)
    {        
        if ($data) {
            curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $sslVerify);
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, $returnTransfer);
    }

}
