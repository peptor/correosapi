<?php

namespace App;

use Illuminate\Support\Facades\Log;

class WScorreosPreRegistroParamsRequest
{
    private $params;

    public function __construct($paramsRequest){
        $this->params = $paramsRequest;
    }


    // *****************************************************
    // Dades de tipus Complexes
    // *****************************************************

    // Dades DatosSMS (TipoSMS)
    private function getTipoSMS($NumeroSMS='', $Idioma=''){
        $sms = []; 
        // Opcionals
        if ($NumeroSMS <> ''){
            $sms['NumeroSMS'] = $NumeroSMS;
        }
        if ($Idioma <> ''){
            $sms['Idioma'] = $Idioma;
        }
        return $sms;
    }

    // Dades Direccion (TipoDireccion)
    private function getTipoDireccion($TipoDireccion='', $Direccion, $Numero='',$Portal='',$Bloque='',$Escalera='',$Piso='',$Puerta='',$Localidad,$Provincia=''){
        $direccion = []; 
        // Obligatoris
        if ($Direccion <> null){
            $direccion['Direccion'] = $Direccion;
        }
        if ($Localidad <> null){
            $direccion['Localidad'] = $Localidad;
        }
        // Opcionals
        if ($TipoDireccion <> ''){
            $direccion['TipoDireccion'] = $TipoDireccion;
        }
        if ($Numero <> ''){
            $direccion['Numero'] = $Numero;
        }
        if ($Portal <> ''){
            $direccion['Portal'] = $Portal;
        }
        if ($Bloque <> ''){
            $direccion['Bloque'] = $Bloque;
        }
        if ($Escalera <> ''){
            $direccion['Escalera'] = $Escalera;
        }
        if ($Piso <> ''){
            $direccion['Piso'] = $Piso;
        }
        if ($Puerta <> ''){
            $direccion['Puerta'] = $Puerta;
        }
        if ($Provincia <> ''){
            $direccion['Provincia'] = $Provincia;
        }

        return $direccion;
    }

    // Dades DatosIdentificacion (Tipoidentificacion)
    // Empresa si no hi ha Nombre
    // PersonaContacto Si empresa
    private function getTipoIdentificacion($Nombre, $Apellido1='', $Apellido2='', $Nif = '', $Empresa = '', $PersonaContacto = ''){
        $identificacion = []; 
        
        // Obligatoris
        if ($Nombre <> null){
            $identificacion['Nombre'] = $Nombre;
        }

        // Opcionals
        if ($Apellido1 <> ''){
            $identificacion['Apellido1'] = $Apellido1;
        }
        if ($Apellido2 <> ''){
            $identificacion['Apellido2'] = $Apellido2;
        }
        if ($Nif <> ''){
            $identificacion['Nif'] = $Nif;
        }
        if ($Empresa <> ''){
            $identificacion['Empresa'] = $Empresa;
        }
        if ($PersonaContacto <> ''){
            $identificacion['PersonaContacto'] = $PersonaContacto;
        }

        return $identificacion;
    }

    // Dades DatosRemitente (TipoRemitente)
    private function getTipoRemitente($DatosIdentificacion, $DatosDireccion, $CP, $Pais = '', $Telefonocontacto = '', $Email = '', $DatosSMS = ''){
        $remitente = [];               

        // Obligatoris
        if ($DatosIdentificacion <> null){
            $remitente['Identificacion'] = $DatosIdentificacion;
        }
        if ($DatosDireccion <> null){
            $remitente['DatosDireccion'] = $DatosDireccion;
        }

        if ($CP <> null){
            $remitente['CP'] = $CP;
        }
        // Opcionals
        if ($Pais <> ''){
            $remitente['Pais'] = $Pais;
        }        
        if ($Telefonocontacto <> ''){
            $remitente['Telefonocontacto'] = $Telefonocontacto;
        }  
        if ($Email <> ''){
            $remitente['Email'] = $Email;
        }  
        if ($DatosSMS <> ''){
            $remitente['DatosSMS'] = $DatosSMS;
        }  

        return $remitente;
    }

    // Dades DatosDestinatario (TipoDestinatario)
    private function getTipoDestinatario($DatosIdentificacion, $DatosDireccion1, $DatosDireccion2='', $CP, $ZIP='', $Pais = '', $Telefonocontacto='', $Email='', $DatosSMS = ''){
        $destinatario = [];               

        // Obligatoris
        if ($DatosIdentificacion <> null){
            $destinatario['Identificacion'] = $DatosIdentificacion;
        }
        if ($DatosDireccion1 <> null){
            $destinatario['DatosDireccion'] = $DatosDireccion1;
        }
        if ($CP <> null){
            $destinatario['CP'] = $CP;
        }

        // Opcionals
        if ($DatosDireccion2 <> ''){
            $destinatario['DatosDireccion2'] = $DatosDireccion2;
        }        
        if ($ZIP <> ''){
            $destinatario['ZIP'] = $ZIP;
        }  
        if ($Pais <> ''){
            $destinatario['Pais'] = $Pais;
        }  
        if ($Telefonocontacto <> ''){
            $destinatario['Telefonocontacto'] = $Telefonocontacto;
        }  
        if ($Email <> ''){
            $destinatario['Email'] = $Email;
        }  
        if ($DatosSMS <> ''){
            $destinatario['DatosSMS'] = $DatosSMS;
        }  

        return $destinatario;
    }    

    // Dades Envio (TipoEnvio)
      private function getTipoEnvio($CodProducto, $TipoFranqueo, $Pesos, $ReferenciaCliente='', $ModalidadEntrega='', $Largo = '', $Alto = '', $Ancho = ''){
        $envio = [];               

        // Obligatoris
        if ($CodProducto <> null){
            $envio['CodProducto'] = $CodProducto;
        }
        if ($TipoFranqueo <> null){
            $envio['TipoFranqueo'] = $TipoFranqueo;
        }
        if ($Pesos <> null){
            $envio['Pesos'] = ['Peso' => $Pesos];
        }

        // Opcionals
        if ($ReferenciaCliente <> ''){
            $envio['ReferenciaCliente'] = $ReferenciaCliente;
        }   
        if ($ModalidadEntrega <> ''){
            $envio['ModalidadEntrega'] = $ModalidadEntrega;
        }   
        if ($Largo <> ''){
            $envio['Largo'] = $Largo;
        }   
        if ($Alto <> ''){
            $envio['Alto'] = $Alto;
        }   
        if ($Ancho <> ''){
            $envio['Ancho'] = $Ancho;
        }   

        return $envio;
    } 

    // Dades Envio Pesos (TipoPeso)
    private function getTipoPeso($TipoPeso, $Valor){
        $tipopeso = [];  
        
        // Obligatoris
        if ($TipoPeso <> null){
            $tipopeso['TipoPeso'] = $TipoPeso;
        }
        if ($Valor <> null){
            $tipopeso['Valor'] = $Valor;
        }

        
        return $tipopeso;
    }

    // *****************************************************
    // Arrays de parametres
    // *****************************************************

    // Dades array DatosRemitente 
    private function getParamsDatosRemitente(){
        $remitente = [];
        // Dades remitent Identificacion
        if (!isset($this->params['RemitenteNombre'])){
            $this->params['RemitenteNombre'] = null;
        }
        if (!isset($this->params['RemitenteApellido1'])){
            $this->params['RemitenteApellido1'] = null;
        }
        if (!isset($this->params['RemitenteApellido2'])){
            $this->params['RemitenteApellido2'] = null;
        }
        if (!isset($this->params['RemitenteNif'])){
            $this->params['RemitenteNif'] = null;
        }
        if (!isset($this->params['RemitenteEmpresa'])){
            $this->params['RemitenteEmpresa'] = null;
        }
        if (!isset($this->params['RemitentePersonaContacto'])){
            $this->params['RemitentePersonaContacto'] = null;
        }
        $remitenteDatosIdentificacion = $this->getTipoIdentificacion($this->params['RemitenteNombre'],
                                                                    $this->params['RemitenteApellido1'],
                                                                    $this->params['RemitenteApellido2'],
                                                                    $this->params['RemitenteNif'],
                                                                    $this->params['RemitenteEmpresa'],
                                                                    $this->params['RemitentePersonaContacto']);
        // Dades remitent DatosDireccion
        if (!isset($this->params['RemitenteTipoDireccion'])){
            $this->params['RemitenteTipoDireccion'] = null;
        }
        if (!isset($this->params['RemitenteDireccion'])){
            $this->params['RemitenteDireccion'] = null;
        }
        if (!isset($this->params['RemitenteNumero'])){
            $this->params['RemitenteNumero'] = null;
        }
        if (!isset($this->params['RemitentePortal'])){
            $this->params['RemitentePortal'] = null;
        }
        if (!isset($this->params['RemitenteBloque'])){
            $this->params['RemitenteBloque'] = null;
        }
        if (!isset($this->params['RemitentePiso'])){
            $this->params['RemitentePiso'] = null;
        }
        if (!isset($this->params['RemitentePuerta'])){
            $this->params['RemitentePuerta'] = null;
        }
        if (!isset($this->params['RemitenteEscalera'])){
            $this->params['RemitenteEscalera'] = null;
        }
        if (!isset($this->params['RemitenteLocalidad'])){
            $this->params['RemitenteLocalidad'] = null;
        }
        if (!isset($this->params['RemitenteProvincia'])){
            $this->params['RemitenteProvincia'] = null;
        }
        $remitenteDatosDireccion = $this->getTipoDireccion($this->params['RemitenteTipoDireccion'],
                                                        $this->params['RemitenteDireccion'],
                                                        $this->params['RemitenteNumero'],
                                                        $this->params['RemitentePortal'],
                                                        $this->params['RemitenteBloque'],
                                                        $this->params['RemitentePiso'],
                                                        $this->params['RemitentePuerta'],
                                                        $this->params['RemitenteEscalera'],
                                                        $this->params['RemitenteLocalidad'],
                                                        $this->params['RemitenteProvincia'],
                                                        );

        if (!isset($this->params['RemitenteNumeroSMS'])){
            $this->params['RemitenteNumeroSMS'] = null;
        }
        if (!isset($this->params['RemitenteIdioma'])){
            $this->params['RemitenteIdioma'] = null;
        }
        $remitenteDatosSMS = $this->getTipoSMS($this->params['RemitenteNumeroSMS'],  $this->params['RemitenteIdioma']);                                                                                 

        if (!isset($this->params['RemitenteCP'])){
            $this->params['RemitenteCP'] = null;
        }
        if (!isset($this->params['RemitentePais'])){
            $this->params['RemitentePais'] = null;
        }
        if (!isset($this->params['RemitenteTelefonocontacto'])){
            $this->params['RemitenteTelefonocontacto'] = null;
        }
        if (!isset($this->params['RemitenteEmail'])){
            $this->params['RemitenteEmail'] = null;
        }
        $remitente = $this->getTipoRemitente($remitenteDatosIdentificacion,
                                            $remitenteDatosDireccion,
                                            $this->params['RemitenteCP'],
                                            $this->params['RemitentePais'],
                                            $this->params['RemitenteTelefonocontacto'],
                                            $this->params['RemitenteEmail'],
                                            $remitenteDatosSMS);
        return $remitente;
        
    }    

    // Dades array DatosDestinatario
    private function getParamsDatosDestinatario(){
        $destinatario= [];
        // Dades remitent Identificacion
        if (!isset($this->params['DestinatarioNombre'])){
            $this->params['DestinatarioNombre'] = null;
        }
        if (!isset($this->params['DestinatarioApellido1'])){
            $this->params['DestinatarioApellido1'] = null;
        }
        if (!isset($this->params['DestinatarioApellido2'])){
            $this->params['DestinatarioApellido2'] = null;
        }
        if (!isset($this->params['DestinatarioNif'])){
            $this->params['DestinatarioNif'] = null;
        }
        if (!isset($this->params['DestinatarioEmpresa'])){
            $this->params['DestinatarioEmpresa'] = null;
        }
        if (!isset($this->params['DestinatarioPersonaContacto'])){
            $this->params['DestinatarioPersonaContacto'] = null;
        }
        $destinatarioDatosIdentificacion = $this->getTipoIdentificacion($this->params['DestinatarioNombre'],
                                                                    $this->params['DestinatarioApellido1'],
                                                                    $this->params['DestinatarioApellido2'],
                                                                    $this->params['DestinatarioNif'],
                                                                    $this->params['DestinatarioEmpresa'],
                                                                    $this->params['DestinatarioPersonaContacto']);
        // Dades remitent DatosDireccion
        if (!isset($this->params['DestinatarioTipoDireccion'])){
            $this->params['DestinatarioTipoDireccion'] = null;
        }
        if (!isset($this->params['DestinatarioDireccion'])){
            $this->params['DestinatarioDireccion'] = null;
        }
        if (!isset($this->params['DestinatarioNumero'])){
            $this->params['DestinatarioNumero'] = null;
        }
        if (!isset($this->params['DestinatarioPortal'])){
            $this->params['DestinatarioPortal'] = null;
        }
        if (!isset($this->params['DestinatarioBloque'])){
            $this->params['DestinatarioBloque'] = null;
        }
        if (!isset($this->params['DestinatarioPiso'])){
            $this->params['DestinatarioPiso'] = null;
        }
        if (!isset($this->params['DestinatarioPuerta'])){
            $this->params['DestinatarioPuerta'] = null;
        }
        if (!isset($this->params['DestinatarioEscalera'])){
            $this->params['DestinatarioEscalera'] = null;
        }
        if (!isset($this->params['DestinatarioLocalidad'])){
            $this->params['DestinatarioLocalidad'] = null;
        }
        if (!isset($this->params['DestinatarioProvincia'])){
            $this->params['DestinatarioProvincia'] = null;
        }
        $destinatarioDatosDireccion = $this->getTipoDireccion($this->params['DestinatarioTipoDireccion'],
                                                        $this->params['DestinatarioDireccion'],
                                                        $this->params['DestinatarioNumero'],
                                                        $this->params['DestinatarioPortal'],
                                                        $this->params['DestinatarioBloque'],
                                                        $this->params['DestinatarioPiso'],
                                                        $this->params['DestinatarioPuerta'],
                                                        $this->params['DestinatarioEscalera'],
                                                        $this->params['DestinatarioLocalidad'],
                                                        $this->params['DestinatarioProvincia'],
                                                        );

        // Dades remitent DatosDireccion2
        if (!isset($this->params['DestinatarioTipoDireccion2'])){
            $this->params['DestinatarioTipoDireccion2'] = null;
        }
        if (!isset($this->params['DestinatarioDireccion2'])){
            $this->params['DestinatarioDireccion2'] = null;
        }
        if (!isset($this->params['DestinatarioNumero2'])){
            $this->params['DestinatarioNumero2'] = null;
        }
        if (!isset($this->params['DestinatarioPortal2'])){
            $this->params['DestinatarioPortal2'] = null;
        }
        if (!isset($this->params['DestinatarioBloque2'])){
            $this->params['DestinatarioBloque2'] = null;
        }
        if (!isset($this->params['DestinatarioPiso2'])){
            $this->params['DestinatarioPiso2'] = null;
        }
        if (!isset($this->params['DestinatarioPuerta2'])){
            $this->params['DestinatarioPuerta2'] = null;
        }
        if (!isset($this->params['DestinatarioEscalera2'])){
            $this->params['DestinatarioEscalera2'] = null;
        }
        if (!isset($this->params['DestinatarioLocalidad2'])){
            $this->params['DestinatarioLocalidad2'] = null;
        }
        if (!isset($this->params['DestinatarioProvincia2'])){
            $this->params['DestinatarioProvincia2'] = null;
        }
        $destinatarioDatosDireccion2 = $this->getTipoDireccion($this->params['DestinatarioTipoDireccion2'],
                                                        $this->params['DestinatarioDireccion2'],
                                                        $this->params['DestinatarioNumero2'],
                                                        $this->params['DestinatarioPortal2'],
                                                        $this->params['DestinatarioBloque2'],
                                                        $this->params['DestinatarioPiso2'],
                                                        $this->params['DestinatarioPuerta2'],
                                                        $this->params['DestinatarioEscalera2'],
                                                        $this->params['DestinatarioLocalidad2'],
                                                        $this->params['DestinatarioProvincia2'],
                                                        );
        if (count($destinatarioDatosDireccion2) == 0) {
            $destinatarioDatosDireccion2 = $destinatarioDatosDireccion;
        }
        if (!isset($this->params['DestinatarioNumeroSMS'])){
            $this->params['DestinatarioNumeroSMS'] = null;
        }
        if (!isset($this->params['DestinatarioIdioma'])){
            $this->params['DestinatarioIdioma'] = null;
        }
        $destinatarioDatosSMS = $this->getTipoSMS($this->params['DestinatarioNumeroSMS'],  $this->params['DestinatarioIdioma']);                                                                                 

        if (!isset($this->params['DestinatarioCP'])){
            $this->params['DestinatarioCP'] = null;
        }
        if (!isset($this->params['DestinatarioZIP'])){
            $this->params['DestinatarioZIP'] = null;
        }
        if (!isset($this->params['DestinatarioPais'])){
            $this->params['DestinatarioPais'] = null;
        }
        if (!isset($this->params['DestinatarioTelefonocontacto'])){
            $this->params['DestinatarioTelefonocontacto'] = null;
        }
        if (!isset($this->params['DestinatarioEmail'])){
            $this->params['DestinatarioEmail'] = null;
        }
        $destinatario = $this->getTipoDestinatario($destinatarioDatosIdentificacion,
                                            $destinatarioDatosDireccion,
                                            $destinatarioDatosDireccion2,
                                            $this->params['DestinatarioCP'],
                                            $this->params['DestinatarioZIP'],
                                            $this->params['DestinatarioPais'],
                                            $this->params['DestinatarioTelefonocontacto'],
                                            $this->params['DestinatarioEmail'],
                                            $destinatarioDatosSMS);
        return $destinatario;
    }

    // Dades array Envio
    private function getParamsListaTipoEnvio(){
        $envio= [];

        // Dades envio CodProducto
        if (!isset($this->params['EnvioCodProducto'])){
            $this->params['EnvioCodProducto'] = null;
        }
        // Dades envio TipoFranqueo
        if (!isset($this->params['EnvioTipoFranqueo'])){
            $this->params['EnvioTipoFranqueo'] = null;
        }
        // Dades envio Pesos
        if (!isset($this->params['EnvioPesoTipoPeso'])){
            $this->params['EnvioPesoTipoPeso'] = null;
        }
        if (!isset($this->params['EnvioPesoValor'])){
            $this->params['EnvioPesoValor'] = null;
        }
        // Dades envio ReferenciaCliente
        if (!isset($this->params['EnvioReferenciaCliente'])){
            $this->params['EnvioReferenciaCliente'] = null;
        }
        // Dades envio Modalidad Entrega
        if (!isset($this->params['EnvioModalidadEntrega'])){
            $this->params['EnvioModalidadEntrega'] = null;
        }
        // Dades envio Largo
        if (!isset($this->params['EnvioLargo'])){
            $this->params['EnvioLargo'] = null;
        }
        // Dades envio Alto
        if (!isset($this->params['EnvioAlto'])){
            $this->params['EnvioAlto'] = null;
        }
        // Dades envio Ancho
        if (!isset($this->params['EnvioAncho'])){
            $this->params['EnvioAncho'] = null;
        }

        $envioPeso = $this->getTipoPeso($this->params['EnvioPesoTipoPeso'],  $this->params['EnvioPesoValor']);      
 
        $envio = $this->getTipoEnvio($this->params['EnvioCodProducto'],
                                     $this->params['EnvioTipoFranqueo'],
                                     $envioPeso,
                                     $this->params['EnvioReferenciaCliente'],
                                     $this->params['EnvioModalidadEntrega'],
                                     $this->params['EnvioLargo'],
                                     $this->params['EnvioAlto'],
                                     $this->params['EnvioAncho']);
        return $envio;
    }

    // Dades array Tipo Valor Añadido
    private function getParamsTipoVA(){
        $valoresAnadidos= [];

        return $valoresAnadidos;
    }

    // ********************************************************************
    // Funcions públiques per obtenir tots els paràmetres per cada funció
    // ********************************************************************

    // Enviament 1 Paquet
    public function getParamsPreRegistro(){
        $IdiomaErrores = null;
        if (isset($this->params['IdiomaErrores'])){
            $IdiomaErrores = $this->params['IdiomaErrores'];
        }
        $TotalBultos = 1;
        if (isset($this->params['TotalBultos'])){
            $TotalBultos = $this->params['TotalBultos'];
        }

        $ModDevEtiqueta = null;
        if (isset($this->params['ModDevEtiqueta'])){
            $ModDevEtiqueta = $this->params['ModDevEtiqueta'];
        }
        
        $Remitente = $this->getParamsDatosRemitente();
        $Destinatario = $this->getParamsDatosDestinatario();
        $Envio = $this->getParamsListaTipoEnvio();
        $EntregaParcial = null;
        if (isset($this->params['EntregaParcial'])){
            $EntregaParcial = $this->params['EntregaParcial'];
        }
        $CodExpedicion = null;
        if (isset($this->params['CodExpedicion'])){
            $CodExpedicion = $this->params['CodExpedicion'];
        }
        $CodManifiesto = null;
        if (isset($this->params['CodManifiesto'])){
            $CodManifiesto = $this->params['CodManifiesto'];
        }

        $preRegistroRequest = [];
        if ($IdiomaErrores <> null){
            $preRegistroRequest += ['IdiomaErrores' => $IdiomaErrores];
        }
        $preRegistroRequest += ['TotalBultos' => $TotalBultos];
        if ($ModDevEtiqueta <> null){
            $preRegistroRequest += ['ModDevEtiqueta' => $ModDevEtiqueta];
        }
        $preRegistroRequest += ['Remitente'  => $Remitente];
        $preRegistroRequest += ['Destinatario' => $Destinatario];
        $preRegistroRequest += ['Envio' => $Envio];
        if ($EntregaParcial <> null){
            $preRegistroRequest += ['EntregaParcial' => $EntregaParcial];
        }
        if ($CodExpedicion <> null){
            $preRegistroRequest += ['CodExpedicion' => $CodExpedicion];
        }
        if ($CodManifiesto <> null){
            $preRegistroRequest += ['CodManifiesto' => $CodManifiesto];
        }

        return $preRegistroRequest;
    }

    // CodEnvio
    // ModDevEtiqueta
    public function getParamsSolicitudEtiqueta(){
        $IdiomaErrores = null;
        if (isset($this->params['IdiomaErrores'])){
            $IdiomaErrores = $this->params['IdiomaErrores'];
        }
        $FechaOperacion = null;
        if (isset($this->params['FechaOperacion'])){
            $FechaOperacion = $this->params['FechaOperacion'];
        }

        $CodEnvio = null;
        if (isset($this->params['CodEnvio'])){
            $CodEnvio = $this->params['CodEnvio'];
        }

        $ModDevEtiqueta = null;
        if (isset($this->params['ModDevEtiqueta'])){
            $ModDevEtiqueta = $this->params['ModDevEtiqueta'];
        }
        

        $solicitudEtiquetaRequest = [];
        if ($IdiomaErrores <> null){
            $solicitudEtiquetaRequest += ['IdiomaErrores' => $IdiomaErrores];
        }
        $solicitudEtiquetaRequest += ['FechaOperacion' => $FechaOperacion];
        $solicitudEtiquetaRequest += ['CodEnvio'  => $CodEnvio];
        $solicitudEtiquetaRequest += ['ModDevEtiqueta' => $ModDevEtiqueta];

        return $solicitudEtiquetaRequest;
    }


    // Enviament multibulto
    public function getParamsPreRegistroMultibulto(){
        $IdiomaErrores = null;
        if (isset($this->params['IdiomaErrores'])){
            $IdiomaErrores = $this->params['IdiomaErrores'];
        }
        $TotalBultos = 1;
        if (isset($this->params['TotalBultos'])){
            $TotalBultos = $this->params['TotalBultos'];
        }

        $ModDevEtiqueta = null;
        if (isset($this->params['ModDevEtiqueta'])){
            $ModDevEtiqueta = $this->params['ModDevEtiqueta'];
        }
        
        $Remitente = $this->getParamsDatosRemitente();
        $Destinatario = $this->getParamsDatosDestinatario();
        $Envios = $this->getParamsListaTipoEnvio();
        $EntregaParcial = null;
        if (isset($this->params['EntregaParcial'])){
            $EntregaParcial = $this->params['EntregaParcial'];
        }
        $CodExpedicion = null;
        if (isset($this->params['CodExpedicion'])){
            $CodExpedicion = $this->params['CodExpedicion'];
        }
        $CodManifiesto = null;
        if (isset($this->params['CodManifiesto'])){
            $CodManifiesto = $this->params['CodManifiesto'];
        }
        $CodProducto = null;
        if (isset($this->params['CodProducto'])){
            $CodProducto = $this->params['CodProducto'];
        }       
        $ReferenciaExpedicion = null;
        if (isset($this->params['ReferenciaExpedicion'])){
            $ReferenciaExpedicion = $this->params['ReferenciaExpedicion'];
        }       
        $ValoresAnadidos = $this->getParamsTipoVA();
        $NotificacionBulto = null;
        if (isset($this->params['NotificacionBulto'])){
            $NotificacionBulto = $this->params['NotificacionBulto'];
        }       
        $ModalidadEntrega = null;
        if (isset($this->params['ModalidadEntrega'])){
            $ModalidadEntrega = $this->params['ModalidadEntrega'];
        }       
        $OficinaElegida = null;
        if (isset($this->params['OficinaElegida'])){
            $OficinaElegida = $this->params['OficinaElegida'];
        }  
        $CodigoHomepaq = null;
        if (isset($this->params['CodigoHomepaq'])){
            $CodigoHomepaq = $this->params['CodigoHomepaq'];
        }  
        $AdmisionHomepaq = null;
        if (isset($this->params['AdmisionHomepaq'])){
            $AdmisionHomepaq = $this->params['AdmisionHomepaq'];
        }  
        $TipoFranqueo = null;
        if (isset($this->params['TipoFranqueo'])){
            $TipoFranqueo = $this->params['TipoFranqueo'];
        }  
        $NumMaquinaFranquear = null;
        if (isset($this->params['NumMaquinaFranquear'])){
            $NumMaquinaFranquear = $this->params['NumMaquinaFranquear'];
        }  
        $ImporteFranqueado = null;
        if (isset($this->params['ImporteFranqueado'])){
            $ImporteFranqueado = $this->params['ImporteFranqueado'];
        }                    
        $preRegistroMultibultoRequest = [];
        if ($IdiomaErrores <> null){
            $preRegistroMultibultoRequest += ['IdiomaErrores' => $IdiomaErrores];
        }
        $preRegistroMultibultoRequest += ['TotalBultos' => $TotalBultos];
        if ($ModDevEtiqueta <> null){
            $preRegistroMultibultoRequest += ['ModDevEtiqueta' => $ModDevEtiqueta];
        }
        $preRegistroMultibultoRequest += ['Remitente'  => $Remitente];
        $preRegistroMultibultoRequest += ['Destinatario' => $Destinatario];
        $preRegistroMultibultoRequest += ['Envios' => $Envios];
        if ($EntregaParcial <> null){
            $preRegistroMultibultoRequest += ['EntregaParcial' => $EntregaParcial];
        }
        if ($CodExpedicion <> null){
            $preRegistroMultibultoRequest += ['CodExpedicion' => $CodExpedicion];
        }
        if ($CodManifiesto <> null){
            $preRegistroMultibultoRequest += ['CodManifiesto' => $CodManifiesto];
        }
        $preRegistroMultibultoRequest += ['CodProducto' => $CodProducto];
        if ($ReferenciaExpedicion <> null){
            $preRegistroMultibultoRequest += ['ReferenciaExpedicion' => $ReferenciaExpedicion];
        }
        if ($ValoresAnadidos <> null){
            $preRegistroMultibultoRequest += ['ValoresAnadidos' => $ValoresAnadidos];
        }
        if ($NotificacionBulto <> null){
            $preRegistroMultibultoRequest += ['NotificacionBulto' => $NotificacionBulto];
        }
        if ($ModalidadEntrega <> null){
            $preRegistroMultibultoRequest += ['ModalidadEntrega' => $ModalidadEntrega];
        }
        if ($OficinaElegida <> null){
            $preRegistroMultibultoRequest += ['OficinaElegida' => $OficinaElegida];
        }
        if ($CodigoHomepaq <> null){
            $preRegistroMultibultoRequest += ['CodigoHomepaq' => $CodigoHomepaq];
        }
        if ($AdmisionHomepaq <> null){
            $preRegistroMultibultoRequest += ['AdmisionHomepaq' => $AdmisionHomepaq];
        }
        $preRegistroMultibultoRequest += ['TipoFranqueo' => $TipoFranqueo];
        if ($NumMaquinaFranquear <> null){
            $preRegistroMultibultoRequest += ['NumMaquinaFranquear' => $NumMaquinaFranquear];
        }
        if ($ImporteFranqueado <> null){
            $preRegistroMultibultoRequest += ['ImporteFranqueado' => $ImporteFranqueado];
        }
        return $preRegistroMultibultoRequest;
    }

}
