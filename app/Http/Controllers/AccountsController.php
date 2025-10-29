<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Lines;
use App\Models\Subscribers;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isInt;
use function PHPUnit\Framework\isNumeric;

class AccountsController extends Controller
{
    use ApiResponse;

    public function save(Request $request)
    {
        $code = 0;
        $clienteId = $request->get('ClienteId');
        $cuenta = $request->get('Cuenta');
        $plan = $request->get('Plan');
        $message = '';
        $code = 0;

        $cliente = Subscribers::find($clienteId);
        if (!$cliente) {
            $code = 200;
            $message = 'El ID del cliente no puede ser nulo o menor a 1';
        }

        if ( (empty($clienteId) || $clienteId <= 0) && $code == 0) {
            $code = 200;
            $message = 'El ID del cliente no puede ser nulo o menor a 1';
        }
        if ( empty($cuenta) && $code == 0) {
            $code = 201;
            $message = 'La cuenta no puede estar vacia';
        }
        if ( empty($plan) && $code == 0) {
            $code = 202;
            $message = 'El plan no puede estar vacio';
        } else {

            if (!is_numeric($plan)) {
                $code = 202;
                $message = 'El plan no puede estar vacio';
            }
        }

        if ( (empty($cuenta) || strlen($cuenta) > 50) && $code == 0) {
            $code = 208;
            $message = 'La cuenta no puede tener mas de 50 caracteres';
        }

        $accountCount = Accounts::where('acc_name', $cuenta)->count();
        if ($accountCount > 0) {
            $code = 203;
            $message = 'Cuenta Existente';
        }

        if ($code == 0) {
            $account = new Accounts();
            $account->acc_company = 1;
            $account->acc_client = $clienteId;
            $account->acc_plan = $plan;
            $account->acc_name = $cuenta;
            $account->acc_startdate = date('Y-m-d H:i:s');
            $account->acc_enddate = null;
            if ($account->save()) {
                $code = 0;
                $message = 'Cuenta creada correctamente';
            } else {
                $code = 999;
                $message = 'Error al crear la Cuenta';
            }
        }
        return $this->return($code, $message);
    }

    public function store(Request $request)
    {
        $cuenta = $request->get('Cuenta');
        $nuevaCuenta = $request->get('NuevaCuenta');
        $message = '';
        $code = 0;

        if (empty($cuenta)) {
            $code = 201;
            $message = 'La cuenta no puede estar vacía';
        } elseif (strlen($cuenta) > 50) {
            $code = 208;
            $message = 'La cuenta no puede tener más de 50 caracteres';
        }

        if ($code == 0 && empty($nuevaCuenta)) {
            $code = 201;
            $message = 'La cuenta nueva no puede estar vacía';
        } elseif ($code == 0 && strlen($nuevaCuenta) > 50) {
            $code = 208;
            $message = 'La cuenta nueva no puede tener más de 50 caracteres';
        }


        $account = Accounts::where('acc_name', $cuenta)->first();
        if ($account !== null) {
            $account->acc_name = $nuevaCuenta;
            if ($account->save()) {
                $code = 0;
                $message = 'Cuenta modificada correctamente';
            } else {
                $code = 999;
                $message = 'Error al modificar la cuenta';
            }
        } else {
            $code = 206;
            $message = 'La cuenta no existe';
        }

        return $this->return($code, $message);
    }

    public function destroy(Request $request)
    {
        $code = 0;
        $message = '';
        $cuenta = $request->get('Cuenta');
        $fechaBaja  = date('Y-m-d H:i:s');
        $account = Accounts::where('acc_name', $cuenta)->first();
        if ($account !== null) {
            $account->acc_enddate = $fechaBaja;
            if ($account->save()) {

                $filasAfectadas = Lines::where('tid_company', 1)
                    ->where('tid_account', $account->acc_id)
                    ->update([
                        'tid_enddate' => $fechaBaja
                    ]);
                $code = 0;
                $message = 'Cuenta modificada correctamente. Se dan de baja ' . $filasAfectadas . ' lineas asociadas';
            } else {
                $code = 999;
                $message = 'Error al modificar la cuenta';
            }
        } else {
            $code = 206;
            $message = 'La cuenta no existe';
        }
        return $this->return($code, $message);
    }
}
