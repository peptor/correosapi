<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


// **************************************************************************
// Controlador per enllaç al serveu web SOAP de correos API preregistroenvios
// https://preregistroenvios.correos.es/InterfazWs/index.html
// **************************************************************************
    class WSSoapCorreosController extends Controller
{
    public $clientSoap;
    public $codigoEtiquetador;
    
    // ********************************************************************
    // Constructor
    // URL, WDSL, Codi Etiquetador, credencials
    // ********************************************************************
    public function __construct( $location, $wsdl, $codigoetiquetador, $credencials = null){
        Log::debug($location);
        Log::debug($wsdl);
        Log::debug($codigoetiquetador);
        Log::debug($credencials);
        $this->codigoEtiquetador = $codigoetiquetador;
        if (extension_loaded('soap')) { 
            try {
                if ($credencials == null){
                    $this->clientSoap = new SoapClient($wsdl, ['exceptions'=>true, 'trace'=>true, 'location'=> $location]);    
                } else {
                    $credencials += ['exceptions'=>true, 'trace'=>true, 'location'=> $location];
                    $this->clientSoap = new SoapClient($wsdl, $credencials);    
                }                
            } catch ( SoapFault $e ) {
                Log::debug($e->getMessage());                
            }
        } 
        else {
            Log::debug("No extension");                    
        }                
    }

    // ********************************************************************
    // Funcions privades
    // ********************************************************************

    // ********************************************************************
    // Passar un objecte a un array
    // ********************************************************************
    private function obj2array($obj) {
        $out = array();
        foreach ($obj as $key => $val) {
          switch(true) {
            case is_object($val):
               $out[$key] = self::obj2array($val);
               break;
            case is_array($val):
               $out[$key] = self::obj2array($val);
               break;
            default:
              $out[$key] = $val;
          }
        }
        return $out;
    }  

    private function guardarFitxerADisc($nom, $path, $dades, &$path_file, &$error){
        $error = '';
        try {
            \Storage::disk('public')->put($path . '/'. $nom, $dades);
            $path_file = storage_path('app/public') . '/etiquetasEnvio\/' . $nom;
        }
        catch (\Exception $e) {
            Log::debug('Fallo Exception - ' . $e->getMessage());
            $error = $e->getMessage();                
            return  false;;
        }
        return true;
    }

    // ********************************************************************
    // Funcions públiques
    // ********************************************************************
    // ********************************************************************
    // Llista de les funcions que publica el Serevi web
    // ********************************************************************
    public function getFunctions(){
        return $this->clientSoap->__getFunctions();
    }


    // ********************************************************************
    // Funció per registrar un enviament
    // Preregistro
    /*
      Parametres: 
        IdiomaErrores: EN o res
        FechaOperacion: dd-mm-yyyy hh:mm:ss datetime(20) (en desuso) Calculat
        CodEtiquetador: CCCC string(4) - Calculat
        NumContrato: No usem CodEtiquitedor
        NumCliente: No usem CodEtiquitador
        Care: 000000 string(6) (en desuso) Calculat
        TotalBultos: integer(2) default 1
        ModDevEtiqueta: formato etiqueta 1.XML - 2.PDF - 3.ZPL
        Remitente: DatosRemitente
         - Identificacion: tipoIdentificacion
            - Nombre: string(300) si no hi ha Empresa
            - Apellido1: string(50) NOOBLIGATORI
            - Apellido2: string(50) NOOBLIGATORI
            - NIF: string(15)  NOOBLIGATORI
            - Empresa: string (50) si no hi ha Nombre
            - PersonaContacto: string(150) si empresa i enviament internacional
         - DatosDireccion: tipoDireccion
            - TipoDireccion: string(3) "C,AV,.."
            - Direccion: string(100)
            - Numero: string(5) NOOBLIGATORI
            - Portal: string(5) NOOBLIGATORI
            - Bloque: string(5) NOOBLIGATORI
            - Escalera: string(5) NOOBLIGATORI
            - Piso: string(5) NOOBLIGATORI
            - Puerta: string(5) NOOBLIGATORI
            - Localidad: string(100)
            - Provincia: string(40) NOOBLIGATORI
         - CP: string(5)
         - ZIP: string(10) per unió Europea
         - Pais: string(2)  per enviament internacional
         - Telefonocontacto: string(15)  si espanyol 9 digits NOOBLIGATORI
         - Email: string(50) NOOBLIGATORI
         - DatosSMS: tipoSMS NOOBLIGATORI
            - NumeroSMS: string(9)
            - Idioma: string(1) ‘1’ – Castellano ‘2’ – Catalán ‘3’ – Euskera ‘4’ – Gallego 
        Destinatario: DatosDestinatario
            - Identificacion: tipoIdentificacion
            - DatosDireccion: tipoDireccion
            - DatosDireccion2: tipoDireccion NOOBLIGATORI
            - CP: string(5)
            - ZIP: string(10)
            - País: string(2)
            - DestinoApartadoPostalinternacional: string 1 NOOBLIGATORI
            - ApartadoPostaldestino: string (6) NOOBLIGATORI
            - Telefonocontacto: string(9.15) NOOBLIGATORI
            - Email: string(50) NOOBLIGATORI
            - DatosSMS: tipoSMS NOOBLIGATORI
        Envio: DatosEnvio
         - CodProductoDB: string(5)
         - TipoFranqueo: String(2)- FP: Franqueo pagado - FM: Franqueo maquina - ES: Metálico - ON: Pago online
         - Pesos: ListaTipoPeso
            - Peso: tipoPeso
                - TipoPeso: string(1) R - Real V - Volumétrico
                - Valor: integer(5)
         - 

    */

    // Ha de rebre per parametre les dades dels parametres en un JSON
    // Remitente array
    // Destinatario array
    // Envio array
    // ********************************************************************
    public function preRegistro($params){

        // Exemple no s'usa en format JSON
        // $paramJSON = '{
        //     "FechaOperacion":"06-03-2021 07:03:53",
        //     "CodEtiquetador":"XXX1",
        //     "Care":"000000",
        //     "ModDevEtiqueta":"2",
        //     "Remitente":{"Identificacion":{"Nombre":"Luis","Apellido1":"Gonzalez","Apellido2":"Perez","Nif":"11111111H"},
        //                  "DatosDireccion":{"Direccion":"Emilio Ferrari","Numero":"42","Piso":"5","Puerta":"B","Localidad":"Madrid","Provincia":"Madrid"},
        //                  "CP":"28017",
        //                  "Telefonocontacto":"913252684",
        //                  "Email":"luis.gonazalez@gmail.com<",
        //                  "DatosSMS":{"NumeroSMS":"696801756","Idioma":"1"}
        //                 },
        //     "Destinatario":{"Identificacion":{"Nombre":"Alberto","Apellido1":"Rojo","Apellido2":"Gonzalez"},"DatosDireccion":
        //                    {"Direccion":"Diagonal","Numero":"8","Piso":"8","Puerta":"A","Localidad":"Barcelona","Provincia":"Barcelona"},"DatosDireccion2":{"Direccion":"","Localidad":""},
        //                    "CP":"08021",
        //                    "Telefonocontacto":"93121345",
        //                    "Email":"xxxxx@me.com",
        //                    "DatosSMS":{"NumeroSMS":"696122436","Idioma":"1"}
        //                 },
        //     "Envio":{"CodProducto":"S0132",
        //              "ReferenciaCliente":"PA00001",
        //              "TipoFranqueo":"FP",
        //              "ModalidadEntrega":"ST",
        //              "Pesos":{"Peso":{"TipoPeso":"R","Valor":"450"}},
        //              "Largo":"12",
        //              "Alto":"10",
        //              "Ancho":"15"
        //             }
        //         }';
     
        // $paramsArray = json_decode($paramJSON, true);
        // // camps calculats o fixes
        // $paramsArray['FechaOperacion'] = $fechaOperacion;
        // $paramsArray['CodEtiquetador'] = $this->codigoEtiquetador;
        // $paramsArray['ModDevEtiqueta'] = '1';
        // $paramsArray['TotalBultos'] = '1';
        // $paramsArray['Care'] = '000000';


        // Exemple paràmetres amb arrays
        // $IdiomaErrores = 'ES';        
        // $TotalBultos = 1;
        // $Remitente = array (
        //     'Identificacion' => array ('Nombre'=>'Luis',
        //     'Apellido1' => 'Gonzalez',
        //     'Apellido2' => 'Perez',
        //     'Nif' => '11111111H'),
        //     'DatosDireccion' => array (
        //         'Direccion'=>'Emilio Ferrari',
        //         'Numero'=>'42',
        //         'Piso'=>'5',
        //         'Puerta'=>'B',
        //         'Localidad'=>'Madrid',
        //         'Provincia' => 'Madrid'              
        //      ),             
        //      'CP'=> '28017',
        //      'Telefonocontacto' => '913252684',
        //      'Email'=>'luis.gonazalez@gmail.com<',
        //      'DatosSMS' => array ('NumeroSMS'=>'696801756', 'Idioma'=>'1')
        // );

        // $Destinatario = array (
        //     'Identificacion' => array ('Nombre'=>'Alberto',
        //     'Apellido1' => 'Rojo',
        //     'Apellido2' => 'Gonzalez'),            
        //     'DatosDireccion' => array (
        //          'Direccion'=>'Diagonal',
        //          'Numero'=>'8',
        //          'Piso'=>'8',
        //          'Puerta'=>'A',
        //          'Localidad'=>'Barcelona',
        //          'Provincia' => 'Barcelona'
        //     ),
        //     'DatosDireccion2' => array (
        //         'Direccion'=>'',
        //         'Localidad'=>''
        //     ),
        //     'CP'=> '08021',
        //     'Telefonocontacto' => '93121345',
        //     'Email'=>'xxxxx@me.com',
        //     'DatosSMS' => array ('NumeroSMS'=>'696122436', 'Idioma'=>'1')
        // );

        // $Envio = array (
        //     'CodProducto'=>'S0132',
        //     'ReferenciaCliente'=>'PA00001',
        //     'TipoFranqueo'=>'FP',
        //     'ModalidadEntrega'=>'ST',
        //     'Pesos' => array('Peso' => array('TipoPeso'=>'R', 'Valor'=>'450')),            
        //     'Largo'=>'12',
        //     'Alto'=>'10',
        //     'Ancho'=>'15'
        // );

        // $XML = [
        //     'FechaOperacion'=> $fechaOperacion,
        //     'CodEtiquetador' => $this->codigoEtiquetador,
        //     'Care' => '000000',
        //     'TotalBultos' => $TotalBultos,
        //     'ModDevEtiqueta' => '2',
        //     'Remitente'=> $Remitente,
        //     'Destinatario'=> $Destinatario,
        //     'Envio'=> $Envio
        // ];

        // Dades per paràmetre. Afegim les calculades o fixes
        $fechaOperacion = new Carbon();
        $fechaOperacion = $fechaOperacion->format('d-m-Y h:m:s');
        $params['FechaOperacion'] = $fechaOperacion;
        $params['CodEtiquetador'] = $this->codigoEtiquetador;
        $params['Care'] = '000000';
                    
        try {
            $result = $this->clientSoap->PreRegistro($params);        
        } catch ( \SoapFault $e){
            Log::debug('Fallo SoapFault - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;

        } catch ( \Exception $e ) {
            Log::debug('Fallo Exception - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;
        }

        $result = self::obj2array($result);
        // Nom del fitxer PDF 
        $nomFitxer = $result['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'];
        $path = 'etiquetasEnvio';
        // Aquí arriba el binari del PDF
        $dades = $result['Bulto']['Etiqueta']['Etiqueta_pdf']['Fichero'];
        if (self::guardarFitxerADisc($nomFitxer, $path, $dades, $ruta_fitxer, $error) == true){
            // Retornem la ruta al fitxer guardat
            $result['Bulto']['Etiqueta']['Etiqueta_pdf']['Fichero'] = $ruta_fitxer;
            $result = json_encode($result, JSON_UNESCAPED_UNICODE);
            return  $result;
        }
        else {
            return $error;
        }
      
    }

    // ********************************************************************
    // Furció per demanar un altre cop l'etiqueta
    // Es pasa el Codi d'enviament
    // ********************************************************************
    public function solicitudEtiqueta($params){

        $params['CodEtiquetador'] = $this->codigoEtiquetador;
        $params['Care'] = '000000';

        try {
            $result = $this->clientSoap->SolicitudEtiquetaOp($params);        
        } catch ( \SoapFault $e){
            Log::debug('Fallo SoapFault - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;

        } catch ( \Exception $e ) {
            Log::debug('Fallo Exception - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;
        }

        $result = self::obj2array($result);       
        // Guardem el fitxer a disk
        $nomFitxer = $result['Bulto']['Etiqueta']['Etiqueta_pdf']['NombreF'];
        $path = 'etiquetasEnvio';
        // Aquí arriba el binari del PDF
        $dades = $result['Bulto']['Etiqueta']['Etiqueta_pdf']['Fichero'];
        if (self::guardarFitxerADisc($nomFitxer, $path, $dades, $ruta_fitxer, $error) == true){
            // Retornem la ruta al fitxer guardat
            $result['Bulto']['Etiqueta']['Etiqueta_pdf']['Fichero'] = $ruta_fitxer;
            $result = json_encode($result, JSON_UNESCAPED_UNICODE);
            return  $result;
        }
        else {
            return $error;
        }        
    }

    // ********************************************************************
    // Funció per anular un enviament ja registrat
    // ********************************************************************
    public function AnularOp($params){
        // Exemlpe parametres en XML
        // $XML = '<PeticionAnular> 
        // <codCertificado>PQXXX10721034750108021H</codCertificado>
        // </PeticionAnular>';

        // Es el CodEnvio de preRegistro

        // Exemple amb paràmetres per SOAP
        // $params = [
        //     'codCertificado'=> 'PQXXX10721034750108021H'
        // ];

        Log::debug($params);
        try {            
            $result = $this->clientSoap->AnularOp($params);        
        } catch ( \SoapFault $e){
            Log::debug('Fallo SoapFault - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;

        } catch ( \Exception $e ) {
            $result = $e->getMessage();                
            return  $result;
        }

        $result = json_encode(self::obj2array($result), JSON_UNESCAPED_UNICODE);
        return  $result;
    }

    // ********************************************************************
    // Funció per si s'envien Varis paquets - No usada
    // Ha de rebre per parametre les dades dels parametres en un JSON
    // Remitente array
    // Destinatario array
    // Envio array
    // ********************************************************************
    public function preRegistroMultibulto($params){

        // Exemple JSON de dades test
        // $paramJSON = '{
        //     "FechaOperacion":"06-03-2021 07:03:53",
        //     "CodEtiquetador":"XXX1",
        //     "Care":"000000",
        //     "ModDevEtiqueta":"2",
        //     "Remitente":{"Identificacion":{"Nombre":"Luis","Apellido1":"Gonzalez","Apellido2":"Perez","Nif":"11111111H"},
        //                  "DatosDireccion":{"Direccion":"Emilio Ferrari","Numero":"42","Piso":"5","Puerta":"B","Localidad":"Madrid","Provincia":"Madrid"},
        //                  "CP":"28017",
        //                  "Telefonocontacto":"913252684",
        //                  "Email":"luis.gonazalez@gmail.com<",
        //                  "DatosSMS":{"NumeroSMS":"696801756","Idioma":"1"}
        //                 },
        //     "Destinatario":{"Identificacion":{"Nombre":"Alberto","Apellido1":"Rojo","Apellido2":"Gonzalez"},"DatosDireccion":
        //                    {"Direccion":"Diagonal","Numero":"8","Piso":"8","Puerta":"A","Localidad":"Barcelona","Provincia":"Barcelona"},"DatosDireccion2":{"Direccion":"","Localidad":""},
        //                    "CP":"08021",
        //                    "Telefonocontacto":"93121345",
        //                    "Email":"xxxxx@me.com",
        //                    "DatosSMS":{"NumeroSMS":"696122436","Idioma":"1"}
        //                 },
        //     "Envio":{"Envios": {"CodProducto":"S0132",
        //                         "ReferenciaCliente":"PA00001",
        //                         "TipoFranqueo":"FP",
        //                         "ModalidadEntrega":"ST",
        //                         "Pesos":{"Peso":{"TipoPeso":"R","Valor":"450"}},
        //                         "Largo":"12",
        //                         "Alto":"10",
        //                         "Ancho":"15"}
        //             }
        //         }';
     
        // $paramsArray = json_decode($paramJSON, true);
        // // camps calculats o fixes
        // $fechaOperacion = new Carbon();
        // $fechaOperacion = $fechaOperacion->format('d-m-Y h:m:s');
        // $paramsArray['FechaOperacion'] = $fechaOperacion;
        // $paramsArray['CodEtiquetador'] = $this->codigoEtiquetador;
        // $paramsArray['ModDevEtiqueta'] = '1';
        // $paramsArray['TotalBultos'] = '1';
        // $paramsArray['Care'] = '000000';

        // Exemple dades amb parametres Array
        // $IdiomaErrores = 'ES';        
        // $TotalBultos = 1;
        // $Remitente = array (
        //     'Identificacion' => array ('Nombre'=>'Luis',
        //     'Apellido1' => 'Gonzalez',
        //     'Apellido2' => 'Perez',
        //     'Nif' => '11111111H'),
        //     'DatosDireccion' => array (
        //         'Direccion'=>'Emilio Ferrari',
        //         'Numero'=>'42',
        //         'Piso'=>'5',
        //         'Puerta'=>'B',
        //         'Localidad'=>'Madrid',
        //         'Provincia' => 'Madrid'              
        //      ),             
        //      'CP'=> '28017',
        //      'Telefonocontacto' => '913252684',
        //      'Email'=>'luis.gonazalez@gmail.com<',
        //      'DatosSMS' => array ('NumeroSMS'=>'696801756', 'Idioma'=>'1')
        // );

        // $Destinatario = array (
        //     'Identificacion' => array ('Nombre'=>'Alberto',
        //     'Apellido1' => 'Rojo',
        //     'Apellido2' => 'Gonzalez'),            
        //     'DatosDireccion' => array (
        //          'Direccion'=>'Diagonal',
        //          'Numero'=>'8',
        //          'Piso'=>'8',
        //          'Puerta'=>'A',
        //          'Localidad'=>'Barcelona',
        //          'Provincia' => 'Barcelona'
        //     ),
        //     'DatosDireccion2' => array (
        //         'Direccion'=>'',
        //         'Localidad'=>''
        //     ),
        //     'CP'=> '08021',
        //     'Telefonocontacto' => '93121345',
        //     'Email'=>'xxxxx@me.com',
        //     'DatosSMS' => array ('NumeroSMS'=>'696122436', 'Idioma'=>'1')
        // );

        // $Envio = array (
        //     'CodProducto'=>'S0132',
        //     'ReferenciaCliente'=>'PA00001',
        //     'TipoFranqueo'=>'FP',
        //     'ModalidadEntrega'=>'ST',
        //     'Pesos' => array('Peso' => array('TipoPeso'=>'R', 'Valor'=>'450')),            
        //     'Largo'=>'12',
        //     'Alto'=>'10',
        //     'Ancho'=>'15'
        // );

        // $XML = [
        //     'FechaOperacion'=> $fechaOperacion,
        //     'CodEtiquetador' => $this->codigoEtiquetador,
        //     'Care' => '000000',
        //     'TotalBultos' => $TotalBultos,
        //     'ModDevEtiqueta' => '2',
        //     'Remitente'=> $Remitente,
        //     'Destinatario'=> $Destinatario,
        //     'Envio'=> $Envio
        // ];
             
        $params['FechaOperacion'] = $fechaOperacion;
        $params['CodEtiquetador'] = $this->codigoEtiquetador;
        $params['Care'] = '000000';
        try {
            $result = $this->clientSoap->PreRegistroMultibulto($params);        
        } catch ( \SoapFault $e){
            Log::debug('Fallo SoapFault - ' . $e->getMessage());
            $result = $e->getMessage();                
            return  $result;

        } catch ( \Exception $e ) {
            $result = $e->getMessage();                
            return  $result;
        }

        $result = self::obj2array($result);
        $result = json_encode($result);
        return  $result;

    }

    /*
    */
    public function procesaLocalizador(){
                      
        $XML = '<localizadorConsulta>
        <codigoPostal>08500</codigoPostal>
        </localizadorConsulta>';
        Log::debug($XML);
        
        try {
            $result = $this->clientSoap->procesaLocalizador($XML);       
            Log::debug('No ha fallat ' . $result);

        } catch ( \SoapFault $e){
            Log::debug('Ha fallat SoapFault - ' . $e->getMessage());
            $result = $e->getMessage();                

        }        
        catch ( \Exception $e ) {
            Log::debug('Ha fallat ' . $e);
            $result = $e->getMessage();                
        }
        return $result;    
    }


}
