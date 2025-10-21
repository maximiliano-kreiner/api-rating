<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Lines;
use App\Traits\ApiResponse;
use DateTime;
use Illuminate\Http\Request;

class LinesController extends Controller
{
    use ApiResponse;

    public function save(Request $request)
    {
        $message = '';
        $code = 0;
        $cuenta = $request->get('Cuenta');
        $lineaTelefonica = $request->get('LineaTelefonica');
        $fecha = $request->get('Fecha');



        if (empty($cuenta)) {
            $code = 300;
            $message = 'La cuenta no puede estar vacia';
        }


        if (empty($lineaTelefonica) && $code == 0) {
            $code = 301;
            $message = 'La linea telefonica no puede estar vacia';
        }

        if (empty($fecha) && $code == 0) {
            $code = 302;
            $message = 'La fecha no puede estar vacia';
        }

        if (!empty($fecha) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fecha;

            if (!$isValid && $code == 0) {
                $code = 302;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        $account = Accounts::where('acc_name', $cuenta)->first();
        // dd( $account->acc_id);
        if ($account === null && $code == 0) {
            $code = 303;
            $message = 'La cuenta no existe';
        } else {

            $lineas = Lines::where('tid_company', 1)->where('tid_id', $lineaTelefonica)->count();
            if ($lineas > 0 && $code == 0) {
                $code = 305;
                $message = 'La linea ya existe';
            } else {
                if ($code == 0) {
                    $linea = new Lines();
                    $linea->tid_company = 1;
                    $linea->tid_account = $account->acc_id;
                    $linea->tid_id = $lineaTelefonica;
                    $linea->tid_name = $lineaTelefonica;
                    $linea->tid_startdate = date('Y-m-d H:i:s', strtotime($fecha));
                    $linea->tid_enddate = null;
                    if ($linea->save()) {
                        $code = 0;
                        $message = 'Linea creada correctamente';
                    } else {
                        $code = 999;
                        $message = 'Error al crear la linea';
                    }
                }
            }
        }

        return $this->return($code, $message);
    }

    public function store(Request $request)
    {
        $code = 0;
        $message = '';
        $lineaTelefonica = $request->get('LineaTelefonica');
        $fecha = $request->get('Fecha');
        $nuevaFechaAlta = $request->get('NuevaFechaAlta');

        if (empty($lineaTelefonica) && $code == 0) {
            $code = 301;
            $message = 'La linea telefonica no puede estar vacia';
        }

        if (empty($fecha) && $code == 0) {
            $code = 303;
            $message = 'La fecha no puede estar vacia';
        }
        if (!empty($fecha) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fecha;

            if (!$isValid && $code == 0) {
                $code = 303;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        if (empty($nuevaFechaAlta) && $code == 0) {
            $code = 303;
            $message = 'La fecha de alta no puede estar vacia';
        }
        if (!empty($nuevaFechaAlta) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $nuevaFechaAlta);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $nuevaFechaAlta;

            if (!$isValid && $code == 0) {
                $code = 303;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        if ($code == 0) {
            $linea = Lines::where('tid_company', 1)
                ->where('tid_id', $lineaTelefonica)
                ->where('tid_startdate', $fecha)
                ->first();

            $linea->tid_startdate = $nuevaFechaAlta;
            if ($linea->save()) {
                $code = 0;
                $message = 'Linea modificada correctamente';
            } else {
                $code = 999;
                $message = 'Error al modificar la linea';
            }
        }
        return $this->return($code, $message);
    }

    public function destroy(Request $request)
    {
        $code = 0;
        $message = '';
        $lineaTelefonica = $request->get('LineaTelefonica');
        $fecha = $request->get('Fecha');
        $fechaBaja = $request->get('FechaBaja');

        if (empty($lineaTelefonica) && $code == 0) {
            $code = 301;
            $message = 'La linea telefonica no puede estar vacia';
        }

        if (empty($fecha) && $code == 0) {
            $code = 303;
            $message = 'La fecha no puede estar vacia';
        }
        if (!empty($fecha) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fecha;

            if (!$isValid && $code == 0) {
                $code = 303;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        if (empty($fechaBaja) && $code == 0) {
            $code = 303;
            $message = 'La fecha de alta no puede estar vacia';
        }
        if (!empty($fechaBaja) && $code == 0) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $fechaBaja);
            $isValid = $d && $d->format('Y-m-d H:i:s') === $fechaBaja;

            if (!$isValid && $code == 0) {
                $code = 303;
                $message = 'El formato de la fecha es inválido. Debe ser YYYY-MM-DD HH:MM:SS';
            }
        };

        if ($code == 0) {
            $linea = Lines::where('tid_company', 1)
                ->where('tid_id', $lineaTelefonica)
                ->where('tid_enddate', $fecha)
                ->first();
            $$linea->tid_enddate = $fechaBaja;
            if ($linea->save()) {
                $code = 0;
                $message = 'Linea modificada correctamente';
            } else {
                $code = 999;
                $message = 'Error al modificar la linea';
            }
        }
        return $this->return($code, $message);
    }
}
