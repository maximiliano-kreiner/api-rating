<?php

namespace App\Http\Controllers;

use App\Models\Subscribers;
use App\Traits\ApiResponse;
use DateTime;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    use ApiResponse;

    public function save(Request $request)
    {
        $code = 0;
        $clienteId = $request->get('ClienteId');
        $apellidos = $request->get('Apellidos');
        $nombres = $request->get('Nombres');
        $fecha = $request->get('Fecha');

        $message = '';
        if (empty($clienteId) || $clienteId <= 0) {
            $code = 100;
            $message = 'El ID del cliente no puede ser nulo o menor a 1';
        }
        if (empty($Apellidos) && empty($nombres) && $code == 0) {
            $code = 101;
            $message = 'El nombre y apellido no pueden estar vacios';
        }
        if (empty($fecha) && $code == 0) {
            $code = 102;
            $message = 'La fecha no puede estar vacia';
        };

        if (!empty($fecha) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fecha;

            if (!$isValid && $code == 0) {
                $code = 102;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        if (empty($apellidos) && strlen($apellidos) > 40) {
            $code = 103;
            $message = 'El apellido no puede ser mayor a 40 caracteres';
        }
        if (empty($nombres) && strlen($nombres) > 40 && $code == 0) {
            $code = 104;
            $message = 'El nombre no puede ser mayor a 40 caracteres';
        }

        if ($code == 0) {

            $clienteSeach = Subscribers::where('cli_id', $clienteId)->first();

            if ($clienteSeach == null) {
                $subscriber = new Subscribers();
                $subscriber->cli_id = $clienteId;
                $subscriber->cli_lastnames = $apellidos;
                $subscriber->cli_names = $nombres;
                $subscriber->cli_datecreated = $fecha;
                if ($subscriber->save()) {
                    $code = 0;
                    $message = 'Cliente creado correctamente';
                } else {
                    $code = 999;
                    $message = 'Error al crear el cliente';
                }
            } else {
                if ($clienteSeach->cli_enddate != null && $code == 0) {
                    $clienteSeach->cli_enddate = null;
                    $clienteSeach->cli_datecreated = $fecha;
                    if ($clienteSeach->save()) {
                        $code = 0;
                        $message = 'Cliente reactivado correctamente';
                    } else {
                        $code = 999;
                        $message = 'Error al reactivar el cliente';
                    }
                } else {
                    $code = 105;
                    $message = 'Cliente existente y activo.';

                }
            }
        }

        return $this->return($code, $message);
    }

    public function store($id, Request $request)
    {

        $code = 0;
        $clienteId = $id;
        $apellidos = $request->get('Apellidos');
        $nombres = $request->get('Nombres');

        if ( empty($clienteId) || $clienteId <= 0) {
            $code = 100;
            $message = 'El ID del cliente no puede ser nulo o menor a 1';
        }
        if ( ( empty($apellidos) || empty($nombres) ) && $code == 0) {
            $code = 101;
            $message = 'El nombre y apellido no pueden estar vacios';
        }

        if ( (empty($apellidos) || strlen($apellidos) > 40) && $code == 0) {
            $code = 103;
            $message = 'El apellido no puede ser mayor a 40 caracteres';
        }
        if ( (empty($nombres) || strlen($nombres) > 40) && $code == 0) {
            $code = 104;
            $message = 'El nombre no puede ser mayor a 40 caracteres';
        }

        if ($code == 0) {
            $cliente = Subscribers::where('cli_id', $clienteId)->first();
            if ($cliente) {
                $cliente->cli_datemodified = date('Y-m-d H:i:s');
                $cliente->cli_lastnames = $apellidos;
                $cliente->cli_names = $nombres;
                if ($cliente->save() && $code == 0) {
                    $code = 0;
                    $message = 'Cliente modificado correctamente';
                } else {
                    $code = 999;
                    $message = 'Error al modificar el cliente';
                }
            } else {
                $code = 999;
                $message = 'Error al modificar el cliente';
            }
        }

        return $this->return($code, $message);
    }

    public function destroy($id, Request $request)
    {
        $code = 0;
        $message = '';
        $clienteId = $id;
        $fechaBaja = $request->get('fechaBaja');
        if (empty($clienteId) || $clienteId <= 0) {
            $code = 100;
            $message = 'El ID del cliente no puede ser nulo o menor a 1';
        }

        if (empty($fechaBaja) && $code == 0) {
            $code = 102;
            $message = 'La fecha no puede estar vacia';
        };

        if (!empty($fechaBaja) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fechaBaja);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fechaBaja;

            if (!$isValid && $code == 0) {
                $code = 102;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };
        if ($code == 0) {
            $cliente = Subscribers::find($id);
            $cliente->cli_enddate =   $fechaBaja;
            if ($cliente->save() && $code == 0) {
                $code = 0;
                $message = 'cliente dado de baja';
            } else {
                $code = 999;
                $message = 'Error al realizar la baja';
            }
        }
        return $this->return($code, $message);
    }
}
