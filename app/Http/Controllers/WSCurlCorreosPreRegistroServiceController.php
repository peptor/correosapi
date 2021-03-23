<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WSCurlCorreosPreRegistroServiceController extends Controller
{

    // Atributs de classe
    private $urlPre = "https://preregistroenviospre.correos.es/preregistroenvios";
    private $credentialsPre = "wusutest:Hl8lwJJt";
    
    private $urlProd = "https://preregistroenvios.correos.es/preregistroenvios";
    private $credentialsProd = "wusutest:Hl8lwJJt";
    
    // FUNCIONS PRIVADES 
    //--------------------------------------------------------------------------------------------
    // Contrucció del XML de la petició al servei
    private function getXMLData($xmlbody){
        $CRLF = chr(13) . chr(10);
        $xml_data =  '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns="http://www.correos.es/iris6/services/preregistroetiquetas">
         <soapenv:Header/>
         <soapenv:Body>' . $CRLF . $xmlbody . $CRLF . '</soapenv:Body>
         </soapenv:Envelope>';
 
         return $xml_data;
    }

    // Petició general a servei de preregistroenvio
    // Pasem les dades XML a enviar i la Accio a fer
    private function doRequest($xml_data, $SOAPAction, $test = false){

        if ($test == true){
            $url = $this->urlPre;
            $credentials = $this->credentialsPre;
        } else {
            $url = $this->urlProd;
            $credentials =  $this->credentialsProd;
        }
        
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_USERPWD,$credentials);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml;charset=utf-8',
        'Content-Length: ' . strlen($xml_data),
        'Authorization: Basic ' . base64_encode($credentials),
        'SOAPAction: '. $SOAPAction));
        //curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
               
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $data = self::xmlResponse($data);
        } else {
            $data = self::xmlResponse($data);
            //$data = htmlspecialchars($data);
        } 
        curl_close($ch);
        return $data;
    }

    // Converteix un XML a un array
    private function XML2Array(\SimpleXMLElement $parent)
    {
        $array = array();
        foreach ($parent as $name => $element) {
            ($node = & $array[$name])
                && (1 === count($node) ? $node = array($node) : 1)
                && $node = & $node[];  
            $node = $element->count() ? self::XML2Array($element) : trim($element);
        }
    
        return $array;
    }
    
    // Tractem la resposta per agafar les dades del Body
    // Retornem la llista en un JSON
    private function xmlResponse($xmlResponse){
        // Busquem el soapenv:body
        $xml_ini = strpos($xmlResponse, "<soapenv:Body>");
        $xmlResponse2 = substr($xmlResponse, $xml_ini + 14 , strlen($xmlResponse));
        $xml_fi = strpos($xmlResponse2, "</soapenv:Body>");
        $xml_orig = substr($xmlResponse2, 0, $xml_fi);
        $xml = simplexml_load_string($xml_orig);
        $array = self::XML2Array($xml);
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    // Funcio obsoleta
    private function xmlError($xmlResponse){
        $response = [
            'codError'=>'0',
            'message'=>''
        ];
        
        $xmlObject = new \SimpleXMLElement($xmlResponse);
        var_dump($xmlObject);
        //return $xmlObject->asXML();

        //Si recibimos un XML-respuesta con un error
        preg_match_all("~\<Error\>(.*?)<\/Error\>~",$xmlResponse,$errorHandler, PREG_SET_ORDER);
        if(isset($errorHandler[0])){
            preg_match_all("~\<Error\>(.*?)<\/Error\>~",$xmlResponse,$errorCod, PREG_SET_ORDER);
            preg_match_all("~\<DescError\>(.*?)<\/DescError\>~",$xmlResponse,$errorDesc, PREG_SET_ORDER);
            $response['codError'] = $errorCod[0][1];
            $response['datos'] = $errorDesc[0][1];
            
        } //Si no recibimos un XML-respuesta con error
        else{
            //Imprimimos el XML-respuesta para comprobaciones
            $response['codError'] = '0';
            $response['datos'] = htmlspecialchars($xmlResponse);
        }        
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    // Validacio de les dades rebudes

    // Obtenir XML dades petició preRegistro
    private function formatPreRegistroRequest($data){

        $fechaOperacion = new Carbon();
        $fechaOperacion = $fechaOperacion->format('d-m-Y h:m:s');               
        $xml_body = '<PreregistroEnvio>
         <FechaOperacion>'. $fechaOperacion .'</FechaOperacion>';

        // hi haurà un sol registre
        $array = json_decode($data);
        $CodEtiquetador = $array['CodEtiquetador'];
        $Care = $array['Care'];
        $ModDevEtiqueta = $array['ModDevEtiqueta'];
        $Care = $array['Care'];
        $Care = $array['Care'];
        $Care = $array['Care'];
        $Remitente = $array['Remitente'];
        
    }

    // FUnCIONS TEST
    //--------------------------------------------------------------------------------------------

    // Ha de rebre per parameter tots els camps obligatoris
    public function preRegistroEnvio(){
        $fechaOperacion = new Carbon();
        $fechaOperacion = $fechaOperacion->format('d-m-Y h:m:s');               
        $xml_body = '<PreregistroEnvio>
         <FechaOperacion>'. $fechaOperacion .'</FechaOperacion>
         <CodEtiquetador>XXX1</CodEtiquetador>
         <Care>000000</Care>
         <ModDevEtiqueta>1</ModDevEtiqueta>
         <Remitente>
         <Identificacion>
         <Nombre>Luis</Nombre>
         <Apellido1>Gonzalez</Apellido1>
         <Apellido2>Perez</Apellido2>
         <Nif>11111111H</Nif>
         </Identificacion>
         <DatosDireccion>
         <Direccion>Emilio Ferrari</Direccion>
         <Numero>42</Numero>
         <Piso>5</Piso>
         <Puerta>B</Puerta>
         <Localidad>Madrid</Localidad>
         <Provincia>Madrid</Provincia>
         </DatosDireccion>
         <CP>28017</CP>
         <Telefonocontacto>913252684</Telefonocontacto>
         <Email>luis.gonazalez@gmail.com</Email>
         <DatosSMS>
         <NumeroSMS>696801756</NumeroSMS>
         <Idioma>1</Idioma>
         </DatosSMS>
         </Remitente>
         <Destinatario>
         <Identificacion>
         <Nombre>Alberto</Nombre>
         <Apellido1>Rojo</Apellido1>
         <Apellido2>Gonzalez</Apellido2>
         </Identificacion>
         <DatosDireccion>
         <Direccion>Diagonal</Direccion>
         <Numero>8</Numero>
         <Piso>8</Piso>
         <Puerta>A</Puerta>
         <Localidad>Barcelona</Localidad>
         <Provincia>Barcelona</Provincia>
         </DatosDireccion>
         <CP>08021</CP>
         <Telefonocontacto>93121345</Telefonocontacto>
         <Email>xxxxx@me.com</Email>
         <DatosSMS>
         <NumeroSMS>696122436</NumeroSMS>
         <Idioma>1</Idioma>
         </DatosSMS>
         </Destinatario>
         <Envio>
         <CodProducto>S0132</CodProducto>
         <ReferenciaCliente>PA00001</ReferenciaCliente>
         <TipoFranqueo>FP</TipoFranqueo>
         <ModalidadEntrega>ST</ModalidadEntrega>
         <Pesos>            
         <Peso>
         <TipoPeso>R</TipoPeso>
         <Valor>450</Valor>
         </Peso>
         </Pesos>
         <Largo>12</Largo>
         <Alto>10</Alto>
         <Ancho>15</Ancho>
         </Envio>
         </PreregistroEnvio>';

        $xml_data = self::getXMLData($xml_body);
        $data = self::doRequest($xml_data, 'PreRegistro', true);

        return view('correos', ['log' => $data, 'peticion'=> $xml_data]);
    }

    // ha de rebre per paràmeter codCertificado
    public function preAnularOp(){

        $codCertificado = 'PQXXX10721017880108021T';
        $xml_body = '<PeticionAnular> 
        <codCertificado>' . $codCertificado . '</codCertificado>
        </PeticionAnular>';

        $xml_data = self::getXMLData($xml_body);              
        $data = self::doRequest($xml_data, 'AnularOP', true);

        return view('correos', ['log' => $data, 'peticion'=> $xml_data]);
    }

    public function preLocalizadorOficinas(){
        ini_set('default_socket_timeout', 600);
        $url = "http://localizadoroficinas.correos.es/localizadoroficinas";
        $codpostal='08500';
        // xml con el código postal
        $cadenaxml='<?xml version="1.0" encoding="UTF-8"?>
            <soapenv:Envelope xmlns:soapenv = "http://schemas.xmlsoap.org/soap/envelope/"
            xmlns = "http://ejb.mauo.correos.es">
            <soapenv:Header/>
            <soapenv:Body>
            <localizadorConsulta>
            <codigoPostal>'.$codpostal.'</codigoPostal>
            </localizadorConsulta>
            </soapenv:Body>
            </soapenv:Envelope>';
        $conexion = curl_init();
        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Content-Type: text/xml;
            charset=utf-8', 'Content-Length: '.strlen($cadenaxml), 'SOAPAction:
            localizadorConsulta'));
        curl_setopt($conexion, CURLOPT_TIMEOUT, 60);
        curl_setopt($conexion, CURLOPT_POSTFIELDS, $cadenaxml);
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER,1);
        $respuesta = curl_exec($conexion);
        var_dump($respuesta);
        //Se muestra el resultado por pantalla
        if (curl_errno($conexion)) {
            $respuesta =  htmlspecialchars($respuesta);
        } else {           
            $respuesta =  htmlspecialchars($respuesta);
        }
        curl_close($conexion);
        return view('correos', ['log' => $respuesta]);
    }

}
