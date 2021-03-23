<?php

namespace App\Http\Controllers;

//use SoapVar;
//use SoapHeader;
use SoapClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\WSSoapCorreosConroller;
use App\WSCorreosPreRegistroParamsRequest;

/*
Tenim aquest marketplace que oferim en modalitat SAAS: https://tgnmarketplace.moneder.store/
Ara hi volem integrar un modul d'enviaments amb correos.es enllaçant amb la seva API.
La integració segurament l'enviarem a una empresa de la India que també ens ha programat la base del marketplace. Però abans d'enviar-ho m'agradaria comprovar i tenir una mica de codi per a comprovar que l'API de correos i les credencials que tenim funcionen.
Aprofitant-me del teu oferiment i tenint en compte que aquesta és una tasca curta, complexe, i no excessivament urgent, et volia proposar que fessis un petit programa amb Laravel o PHP que fes alguna consulta a correos i comprovessis que retorna algu. 
Per exemple, podries intentar obtenir la nostra tarifa amb la funció CalculaTarifa

Informacions API: 
https://www.correos.es/es/es/empresas/ecommerce/refuerza-la-logistica-de-tu-ecommerce/integracion-api
https://preregistroenvios.correos.es/InterfazWs/index.html

PREPRODUCCION:
Usuario: wusutest
Password: Hl8lwJJt
código de cliente etiquetador: XXX1
PRODUCCION:
Usuario: w81170272
Password: AUXPz3qZ
código de cliente etiquetador: 78Y1
Num contrato: 54043573 - Num cliente: 81170272 
*/


class correosController extends Controller
{
                       
    // ********************************************************************
    // Dades per test
    // ********************************************************************
    public $wsdlPre = "https://preregistroenviospre.correos.es/preregistroenvios?wsdl";
    public $locationPre = "https://preregistroenviospre.correos.es/preregistroenvios";
    public $credencialsPre = ['login'=>'wusutest', 'password'=>'Hl8lwJJt'];
    public $codEtiquitadorPre = 'XXX1';
    // ********************************************************************

    // ********************************************************************
    // Dades per Producció
    // ********************************************************************
    public $wsdl = "https://preregistroenvios.correos.es/preregistroenvios?wsdl";
    public $location = "https://preregistroenvios.correos.es/preregistroenvios";
    public $credencials = ['login'=>'w81170272', 'password'=>'AUXPz3qZ'];
    public $codEtiquitador = '78Y1';
    // ********************************************************************

    private $clientWsCorreos;

    // ********************************************************************
    // Funcions privades
    // ********************************************************************
       
    // Connexió   
    // WSDL, codi Etiquitador,  Credencials
    private function autentificarPreproducio(){
        $this->clientWsCorreos  = new WSSoapCorreosController($this->locationPre, $this->wsdlPre, $this->codEtiquitadorPre, $this->credencialsPre);               
    }        
    // Producció
    private function autentificarProducio(){
        $this->clientWsCorreos  = new WSSoapCorreosController($this->locationPre, $this->wsdl, $this->codEtiquitador, $this->credencials);        
    }        
    

    // a params hi ha totes les dades en un Array
    // IdiomaErrores
    // TotalBultos
    
    // RemitenteNombre
    // RemitenteApellido1
    // RemitenteApellido2
    // RemitenteNif
    // RemitenteEmpresa
    // RemitentePersonaContacto
    
    // RemitenteTipoDireccion
    // RemitenteDireccion
    // RemitenteNumero
    // RemitentePortal
    // RemitenteBloque
    // RemitenteEscalera
    // RemitentePiso
    // RemitentePuerta
    // RemitenteLocalidad
    // RemitenteProvincia

    // RemitenteCP
    // RemitentePais
    // RemitenteTelefonocontacto
    // RemitenteEmail
    
    // RemitenteNumeroSMS
    // RemitenteIdioma
   
    private function getParamsPreRegistroMultibulto($params){       
        $datosPreRegistroParamsRequest = new WScorreosPreRegistroParamsRequest($params);
        $paramsPreRegistroMultibulto = $datosPreRegistroParamsRequest->getParamsPreRegistroMultibulto();

        return $paramsPreRegistroMultibulto;
    }

    private function getParamsPreRegistro($params){       
        $datosPreRegistroParamsRequest = new  WScorreosPreRegistroParamsRequest($params);
        $paramsPreRegistro = $datosPreRegistroParamsRequest->getParamsPreRegistro();

        return $paramsPreRegistro;
    }

    private function getParamsSolicitudEtiqueta($params){       
        $datosSolicitudEtiquetaParamsRequest = new  WScorreosPreRegistroParamsRequest($params);
        $paramsSolicitudEtiqueta = $datosSolicitudEtiquetaParamsRequest->getParamsSolicitudEtiqueta();

        return $paramsSolicitudEtiqueta;
    }

    // Funcions públiques
    // Vista PROVES
    public function obrirView(){    
        return view('correos', ['log'=>'']);
    }

    // Pre Producció
    public function llistaFuncions($test = 0){
        if ($test == 1){
            $this->clientWsCorreos  = new WSSoapCorreosController($this->locationPre, $this->wsdlPre, $this->codEtiquitadorPre, $this->credencialsPre);        
        } else {
            $this->clientWsCorreos  = new WSSoapCorreosController($this->location, $this->wsdl, $this->codEtiquitador, $this->credencials);        
        }
        
        $llista = $this->clientWsCorreos->getFunctions();
        $log = '<br/>';
        foreach($llista as $i => $funcio){
            $log .=  '(' . $i . ') ' . $funcio . '<br/>';
        }        
        return view('correos', ['log' => $log]);
    }        

    // parametres mínims
    // FechaOperacion
    // CodEtiquetador
    // Care
    // TotalBultos
    // ModDevEtiqueta
    // Remitente
    // RemitenteIdentificacion
    // RemitenteIdentificacionNombre
    // RemitenteIdentificacionApellido1 No
    // RemitenteIdentificacionApellido2 No
    // RemitenteIdentificacionNif No
    // RemitenteDatosDireccionDireccion
    // RemitenteDatosDireccionLocalidad
    // RemitenteCP
    // RemitenteZIP ?
    // RemitentePais ?
    // Destinatario
    // DestinatarioIdentificacion
    // DestinatarioIdentificacionNombre
    // DestinatarioIdentificacionApellido1 No
    // DestinatarioIdentificacionApellido2 No
    // DestinatarioIdentificacionNif No
    // DestinatarioDatosDireccionDireccion
    // DestinatarioDatosDireccionLocalidad
    // DestinatarioCP
    // DestinatarioZIP ?
    // DestinatarioPais ?
    // Envio
    // EnvioCodProducto
    // EnvioTipoFranqueo
    // EnvioPeso
    // EnvioPesoTipoPeso
    // EnvioPesoValor
    // EnvioLargo
    // EnvioAlto
    // EnvioAncho
    // EnvioAduana 
    // EnvioAduanaTipoEnvio
    // EnvioAduanaDescAduanera
    // EnvioAduanaDescAduaneraDatosAduana
    // EnvioAduanaDescAduaneraDatosAduanaCantidad
    // EnvioAduanaDescAduaneraDatosAduanaDescripcion
    // EnvioAduanaDescAduaneraDatosAduanaPesoneto
    // EnvioAduanaDescAduaneraDatosAduanaValorneto
   
    public function PreRegistroEnvio($test = 0){     
        if ($test == 1){
            self::autentificarPreproducio();   
        } else {
            self::autentificarProducio();   
        }
        
        $params['IdiomaErrores'] = null;
        $params['ModDevEtiqueta'] = '2';
        $params['RemitenteNombre'] = 'Luís';
        $params['RemitenteDireccion'] = 'Emilio Ferrari';
        $params['RemitenteCP'] = '28017';
        $params['RemitenteLocalidad'] = 'Madrid';
        $params['DestinatarioNombre'] = 'Alberto';
        $params['DestinatarioDireccion'] = 'Diagonal';
        $params['DestinatarioCP'] = '08021';
        $params['DestinatarioLocalidad'] = 'Barcelona';
        $params['EnvioCodProducto'] = 'S0132';
        $params['EnvioPesoTipoPeso'] = 'R';        
        $params['EnvioPesoValor'] = '450';        
        $params['EnvioModalidadEntrega'] = 'ST';
        $params['EnvioTipoFranqueo'] = 'FP';
        
        if ( $this->clientWsCorreos != null) {
            try {
               // Construim els parametres               
               $params = self::getParamsPreRegistro($params); 
               Log::debug($params);
               $log = $this->clientWsCorreos->preRegistro($params);
               Log::debug($log);
            }        
            catch (Exception $ex){
                $log = $ex->message;
            }                
        }           
        $logArray = json_decode($log, true);   
        $etiqueta = null;
        if(isset($logArray['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'])) {
            $etiqueta = storage_path('app/public') . '/etiquetasEnvio\/' . $logArray['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'];
        }
        Log::debug($etiqueta);
        return view('correos', ['log' => $log, 'etiqueta' => $etiqueta]);
    }
    
    public function SolicitudEtiqueta($test = 0){     

        if ($test == 1){
            self::autentificarPreproducio();   
        } else {
            self::autentificarProducio();   
        }        $params['IdiomaErrores'] = null;
        $params['ModDevEtiqueta'] = '2';
        $params['CodEnvio'] = 'PQXXX10721034750108021H';

        if ( $this->clientWsCorreos != null) {
            try {
               // Construim els parametres               
               $params = self::getParamsSolicitudEtiqueta($params); 
               Log::debug($params);
               $log = $this->clientWsCorreos->solicitudEtiqueta($params);
               Log::debug($log);
            }        
            catch (Exception $ex){
                $log = $ex->message;
            }                
        }           
        $logArray = json_decode($log, true);   
        $etiqueta = null;
        if(isset($logArray['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'])) {
            $etiqueta = storage_path('app/public') . '/etiquetasEnvio\/' . $logArray['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'];
        }
        return view('correos', ['log' => $log, 'etiqueta' => $etiqueta]);
    }

    public function AnularOp($test = 0){     
        if ($test == 1){
            self::autentificarPreproducio();   
        } else {
            self::autentificarProducio();   
        }
        
        $params['codCertificado'] = 'PQXXX10721034750108021H';
        
        if ( $this->clientWsCorreos != null) {
            try {
                if ( $this->clientWsCorreos != null) {
                    try {
                    $log = $this->clientWsCorreos->AnularOp($params);
                    }        
                    catch (Exception $ex){
                        $log = $ex->message;
                    }                
                }           
                return view('correos', ['log' => $log]);
            }
            catch (Exception $ex){
                $log = $ex->message;
            }   
        }           
                
        return view('correos', ['log' => $log]);
    }    

    public function preLocalizadorOficinas(){     
                
        try {
            $url = "http://localizadoroficinas.correos.es/localizadoroficinas";
            $client = new SoapClient( "localizadorMAUO_1.wsdl",array( 'trace' => true,
                            'exceptions' => true,
                            'location' => $url ));
            $codpos=array('codigoPostal' => '08500');
            $log =$client->procesaLocalizador($codpos);

/*
            $this->clientWsCorreos  = new WSSoapCorreosController("http://localizadoroficinaspre.correos.es/localizadoroficinas", 
            $this->codEtiquitadorPre, $this->credencialsPre); 
            if ( $this->clientWsCorreos != null) {
                try {
                    $codpos=array('codigoPostal' => '08500');
                    $log = $this->clientWsCorreos->procesaLocalizador($codpos);
                }        
                catch (Exception $ex){
                    $log = $ex->message;
                }
            }
            */
        }
        catch (Exception $ex){
            $log = $ex->message;
        }               
                
        return view('correos', ['log' => $log]);
    }
   
}
