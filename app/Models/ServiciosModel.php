<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiciosModel extends Model
{
    use HasFactory;

    public function getServiciosInicio() {
      $servicios = DB::table("servicios")
                      ->select("*")
                      ->get();
                      
      return $servicios;
    }

    public function getServicioId($codigo) {
      $servicios = DB::table("servicios")
                      ->select("*")
                      ->where("codigo_servicio", $codigo)
                      ->first();
                      
      return $servicios;
    }

    public function CrearDeportista($deportistas) {
      $deportista = [
        "tipo_documento" => $deportistas["tipo_documento"],
        "documento" => $deportistas["documento"],
        "nombre" => $deportistas["nombre"],
        "apellido" => $deportistas["apellido"],
        "departamento" => $deportistas["departamento"],
        "ciudad" => $deportistas["ciudad"],
        "direccion" => $deportistas["direccion"],
        "telefono" => $deportistas["telefono"],
        "email" => $deportistas["correo_electronico"],
        "password" =>  $deportistas["password"],
        "rol_usuario" => $deportistas["rol_usuario"],
        "acompanantes" => $deportistas["acompanantes"],
        "rh" => $deportistas["rh"],
        "seguro_medico" => $deportistas["seguro_medico"],
        "fecha_nacimiento" => $deportistas["fecha_nacimiento"],
        "estado" => $deportistas["estado"],
        "fecha" => $deportistas["fecha"],
        "hora" => $deportistas["hora"],
      ];

      DB::table("users")
         ->insert($deportista);
    }

    public function CrearIncripciones($servicios) {
      $servicio =[
        "tpdocumento" => $servicios["tpdocumento"],
        "documento" => $servicios["documento"],
        "total" => $servicios["total"],
        "cantidad" => $servicios["cantidad"],
        "envio" => $servicios["envio"],
        "acepta_termino1" => $servicios["acepta_termino1"],
        "acepta_termino2" => $servicios["acepta_termino2"],
        "codigo_promocional" => $servicios["codigo_promocional"],
        "fecha" => $servicios["fecha"],
        "hora" => $servicios["hora"],
        "estado" => $servicios["estado"],
      ]; 
      
      
      $id = DB::table("inscripciones")
         ->insertGetId($servicio);

      return $id;
    }

    public function detalleInscripcion($inscripciones) {
      $inscripcion = [
        "codigo_inscripcion" => $inscripciones["codigo_inscripcion"],
        "codigo_producto" => $inscripciones["codigo_producto"],
        "categoria" => $inscripciones["categoria"],
        "jersy" => $inscripciones["jersy"],
        "kit" => $inscripciones["kit"],
        "precio" => $inscripciones["precio"],
        "cantidad" => $inscripciones["cantidad"],
        "codigo_promocional" => $inscripciones["codigo_promocional"],
        "codigo_qr" => $inscripciones["codigo_qr"],
      ];

      DB::table("detalle_inscripciones")
      ->insert($inscripcion);
    }

    public function getDepartamentos() {
      $departamentos = DB::table("departamentos")
                          ->select("*")
                          ->get();

      return $departamentos;
    }

    public function getUsuariosAdministrador() {
      $usuarios = DB::table("users")
                      ->select("*")
                      ->where("rol_usuario", "Administrador")
                      ->get();
                      
      return $usuarios;
    }

    public function getUsuariosDeportistas() {
      $usuarios = DB::table("users")
                      ->select("users.*", "departamentos.descripcion as departamento")
                      ->join("departamentos", "users.departamento","departamentos.codigo_departamento")
                      ->where("rol_usuario", "Deportista")
                      ->get();
                      
      return $usuarios;
    }

    public function codigosPromocionales() {
      $codigos = DB::table("codigo_promocionales")
                    ->select("*")
                    ->get();
                    
      return $codigos;
    }

    public function getPatrocinios() {
      $patrocinios = DB::table("patrocinios")
                    ->select("*")
                    ->get();
                    
      return $patrocinios;
    }

    public function getInscripciones() {
      $inscripcion = DB::table("inscripciones")
                    ->select("inscripciones.*", "users.telefono")
                    ->join("users", "inscripciones.documento", "users.documento")
                    ->get();
                    
      return $inscripcion;
    }

    public function getDetalleInscripciones($codigo) {
      $detalle = DB::table("detalle_inscripciones")
                    ->select("detalle_inscripciones.*", "servicios.nombre")
                    ->join("servicios", "detalle_inscripciones.codigo_producto", "servicios.codigo_servicio")
                    ->where("codigo_inscripcion", $codigo)
                    ->get();
                    
      return $detalle;
    }

    public function getDatosInscripcion($documento) {
      $datos = DB::table("users")
                  ->select("users.*", "inscripciones.*", "departamentos.descripcion as departamento")
                  ->join("inscripciones","users.documento", "inscripciones.documento")
                  ->join("departamentos","users.departamento", "departamentos.codigo_departamento")
                  ->where("users.documento", $documento)
                  ->get();

      return $datos;
      
    }

    public function crearUsuario() {

    }

    public function crearRegalo($data) {
      $regalo = [
        "codigo_promocional" => $data['codigo_promocional'],
        "precio" => $data['precio'],
        "estado" => $data['estado'],
        "documento" => $data['documento'],
      ];
      DB::table("codigo_promocionales")
         ->insert($regalo);
    }

    public function crearServicio($data) {
      $servicio = [
        "ip" => $data['ip'],
        "url_imagen" => $data['url_imagen'],
        "nombre" => $data['nombre'],
        "precio" => $data['precio'],
        "stock" => $data['stock'],
        "vistas" => $data['vistas'],
        "estado" => $data['estado'],
      ];
      DB::table("servicios")
         ->insert($servicio);
    }

    public function crearPatrocinio($data) {
      $patrocinios = [
        "nombre" => $data['nombre'],
        "logo" => $data['logo'],
        "descripcion" => $data['descripcion'],
        "valor" => $data['valor'],
      ];
      DB::table("patrocinios")
         ->insert($patrocinios);
    }

    public function EntregaKits($tpdocumento,$documento, $estado) {
      $kits = [
        "estado" => $estado
      ];

      DB::table("inscripciones")
         ->where("tpdocumento", $tpdocumento)
         ->where("documento", $documento)
         ->update($kits);
    }

    public function cambiarEstadoPedido($tpdocumento,$documento, $estado) {
      $kits = [
        "estado" => $estado
      ];

      DB::table("inscripciones")
         ->where("tpdocumento", $tpdocumento)
         ->where("documento", $documento)
         ->update($kits);
    }

    public function canjearCodigoPromocional($codigo, $documento) {
      $promocional = DB::table("codigo_promocionales")
                       ->select("*")
                       ->where("codigo_promocional", $codigo)
                       ->where("documento", $documento)
                       ->where("estado", "Activo")
                       ->get();

      return $promocional;
    }

    public function cambiarEstadoPromocional($codigo) {
      $canjeo = [
        "estado" => "Canjeado"
      ];
      DB::table("codigo_promocionales")
         ->where("codigo_promocional", $codigo)
         ->update($canjeo);
    }
}
