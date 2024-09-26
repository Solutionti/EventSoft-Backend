<?php

namespace App\Http\Controllers;
use App\Models\ServiciosModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ServiciosController extends Controller
{

    public function __construct() {
        $this->Servicios = new ServiciosModel();
    }

    public function getServiciosInicio() {
      return  $this->Servicios->getServiciosInicio();
    }

    public function getServicioId(Request $request) {
      $codigo = $request->codigo;

      return  $this->Servicios->getServicioId($codigo);
    }

    public function CrearInscripcionesDetalle(Request $request) {
      
      DB::beginTransaction();
      
      try {

      $nombre = $request->nombre;
      $apellido = $request->apellido;
      $tpdocumento = $request->tpdocumento;
      $documento = $request->documento;
      $departamento = $request->departamento;
      $ciudad = $request->ciudad;
      $direccion = $request->direccion;
      $telefono = $request->telefono;
      $correo = $request->correo;
      $acompanantes = $request->acompanantes;
      $rh = $request->rh;
      $seguro_medico = $request->seguro_medico;
      $fechanacimiento = $request->fechanacimiento;
      // 
      $detalle_carrito = $request->detalle_carrito;
      $total = $request->total;
      $envio = $request->envio;
      $codigo_promocional = $request->codigo_promocional;

      $data1 = [
        "tipo_documento" => $tpdocumento,
        "documento" => $documento,
        "nombre" => $nombre,
        "apellido" => $apellido,
        "departamento" => $departamento,
        "ciudad" => $ciudad,
        "direccion" => $direccion,
        "telefono" => $telefono,
        "correo_electronico" => $correo,
        "password" => Hash::make($documento),
        "rol_usuario" => 'Deportista',
        "acompanantes" => $acompanantes,
        "rh" => $rh,
        "seguro_medico" => $seguro_medico,
        "fecha_nacimiento" => $fechanacimiento,
        "estado" => 'Activo',
        "fecha" => date('Y-m-d'),
        "hora" => date('H:i'),
      ];


      $data2 = [
        "tpdocumento" => $tpdocumento,
        "documento" => $documento,
        "total" => $total,
        "cantidad" => count($detalle_carrito),
        "envio" => $envio,
        "acepta_termino1" => 'Si',
        "acepta_termino2" => 'Si',
        "codigo_promocional" => $codigo_promocional,
        "fecha" => date('Y-m-d'),
        "hora" => date('H:i'),
        "estado" => 'Pendiente',
      ];
      

      $this->Servicios->CrearDeportista($data1);
      $id = $this->Servicios->CrearIncripciones($data2);

      foreach($detalle_carrito as $carrito) {
        $data3 = [
          "codigo_inscripcion" => $id,
          "codigo_producto" => $carrito["codigo"],
          "categoria" => $carrito["categoria"],
          "jersy" => $carrito["jersy"],
          "kit" => 'Si',
          "precio" => $carrito["precio"],
          "cantidad" => $carrito["cantidad"],
          "codigo_promocional" => $codigo_promocional,
          "codigo_qr" => '1234567',
        ];
        $this->Servicios->detalleInscripcion($data3);
      }

      if($codigo_promocional != "") {
        $this->Servicios->cambiarEstadoPromocional($codigo_promocional);
      }
      DB::commit();

      return response()->json(
        [
          'message' => "",
          'status' => 200
        ]
      , 200);
    }
    catch(\Exception $e) {
      DB::rollBack();
      return response()->json(
        [
          'message' => "",
          'status' => 400
        ]
      , 400);
    } 

    }

    public function getDepartamentos() {
      return $this->Servicios->getDepartamentos();
    }

    public function getUsuariosAdministrador() {
      return $this->Servicios->getUsuariosAdministrador();
    }

    public function codigosPromocionales() {
      return $this->Servicios->codigosPromocionales();
    }

    public function getPatrocinios() {
      return $this->Servicios->getPatrocinios();
    }

    public function getUsuariosDeportistas() {
      return $this->Servicios->getUsuariosDeportistas();
    }

    public function getInscripciones() {
      return $this->Servicios->getInscripciones();
    }

    public function getDetalleInscripciones(Request $request) {
      $codigo = $request->codigo;
      return $this->Servicios->getDetalleInscripciones($codigo);
    }

    public function getDatosInscripcion(Request $request) {
      $codigoqr = $request->codigoqr;
      $documento = $request->documento;
      try {

        $datos =  $this->Servicios->getDatosInscripcion($documento);
        
        if(!$datos->isEmpty()) {
          return response()->json([
            'message' => 'Deportista encontrado en la base de datos',
            'data' => $datos,
            'status' => 200
          ]);
        }
        else {
          throw new \Exception('No se ha encontrado el deportista en la base de datos');
        }


      }
        catch(\exception $e) {
          return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
          ]);
        } 
    }

    public function crearUsuario(Request $request) {
      try {
        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $telefono = $request->telefono;
        $documento = $request->documento;
        $correo = $request->correo;
        $tpdocumento = "CC";
        $departamento = "19";
        $ciudad = "IBAGUE";
        $direccion = "DIRECCION SIN IDENTIFICAR";
        $acompanantes = "0";
        $rh = "O+";
        $seguro_medico = "SALUD TOTAL";
        $fechanacimiento = "1993-12-26";
  
        $data1 = [
          "tipo_documento" => $tpdocumento,
          "documento" => $documento,
          "nombre" => $nombre,
          "apellido" => $apellido,
          "departamento" => $departamento,
          "ciudad" => $ciudad,
          "direccion" => $direccion,
          "telefono" => $telefono,
          "correo_electronico" => $correo,
          "password" => Hash::make($documento),
          "rol_usuario" => 'Administrador',
          "acompanantes" => $acompanantes,
          "rh" => $rh,
          "seguro_medico" => $seguro_medico,
          "fecha_nacimiento" => $fechanacimiento,
          "estado" => 'Activo',
          "fecha" => date('Y-m-d'),
          "hora" => date('H:i'),
        ];
        $this->Servicios->CrearDeportista($data1);
        
        return response()->json(
          [
            'message' => "El usuario se ha creado en la base de datos",
            'status' => 200
          ], 200);
      }
      catch(\exception $e) {
        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function crearRegalo(Request $request) {
    
      try {

        $regalo = [
          "codigo_promocional" => $request->codigo_promocional,
          "precio" => $request->precio,
          "estado" => $request->estado,
          "documento" => $request->documento,
        ];
  
        $this->Servicios->crearRegalo($regalo);

        return response()->json([
          'message' => 'El codigo promocional se ha creado correctamente',
          'status' => 200
        ]);

      }
      catch(\exception $e) {

        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
        ]);

      } 
    }

    public function crearServicio(Request $request) {
      try {
        // *********************************************
        $imagen = $request->file("url_imagen");
        $nombreimagen = $imagen->getClientOriginalName();
        $ruta = public_path("servicios/");
        $rutacompleta = $request->nombre.'-'.$nombreimagen;
        copy($imagen->getRealPath(),$ruta.$rutacompleta);
        // *********************************************
        $servicio = [
          "ip" => $request->ip,
          "url_imagen" => $rutacompleta,
          "nombre" => $request->nombre,
          "precio" => $request->precio,
          "stock" => $request->stock,
          "vistas" => $request->vistas,
          "estado" => $request->estado,
        ];

        $this->Servicios->crearServicio($servicio);

        return response()->json([
          'message' => 'El servicio se ha creado correctamente',
          'status' => 200
        ]);

      }
      catch(\exception $e) {

        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
      ]);

      } 
    }

    public function crearPatrocinio(Request $request) {
      try {
        // *********************************************
        $imagen = $request->file("logo");
        $nombreimagen = $imagen->getClientOriginalName();
        $ruta = public_path("patrocinios/");
        $rutacompleta = $request->nombre.'-'.$nombreimagen;
        copy($imagen->getRealPath(),$ruta.$rutacompleta);
        // **********************************************

        $patrocinios = [
          "nombre" => $request->nombre,
          "logo" => $rutacompleta,
          "descripcion" => $request->descripcion,
          "valor" => $request->valor,
        ];

        $this->Servicios->crearPatrocinio($patrocinios);

        return response()->json([
          'message' => 'El patrocinio se ha creado correctamente',
          'status' => 200
        ]);

      }
      catch(\exception $e) {

        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
      ]);

      }
    }

    public function EntregaKits(Request $request) {
      $tpdocumento = $request->tpdocumento;
      $documento = $request->documento;
      $estado = "Entregado";

      try {
        $this->Servicios->EntregaKits($tpdocumento,$documento, $estado);

        return response()->json([
          'message' => 'Se ha hecho entrega de insumos deportivos al inscrito',
          'status' => 200
        ]);
      }
      catch(\exception $e) {
        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function cambiarEstadoPedido(Request $request) {
      $tpdocumento = $request->tpdocumento;
      $documento = $request->documento;
      $estado = $request->estado;

      try {
        $this->Servicios->cambiarEstadoPedido($tpdocumento,$documento, $estado);

        return response()->json([
          'message' => 'El estado del pedido ha cambiado correctamente',
          'status' => 200
        ]);
      }
      catch(\exception $e) {
        return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
        ]);
      }

    }

    public function canjearCodigoPromocional(Request $request) {
      $codigo =  $request->codigo;
      $documento =  $request->documento;
      
      
      $promocional = $this->Servicios->canjearCodigoPromocional($codigo, $documento);
      
      if($promocional->isEmpty()) {
        return response()->json([
          'message' => 'EL codigo ingresado no se encuentra disponible',
          'status' => 400
        ]);
      }
      else {
        return response()->json([
          'message' => 'Se ha encontrado un codigo promocional para el deportista',
          'data' => $promocional,
          'status' => 200
        ]);
      }
    }

    public function getPedidoDeportista(Request $request) {
      $documento = $request->documento;

      return $this->Servicios->getPedidoDeportista($documento);
    }
   
}
