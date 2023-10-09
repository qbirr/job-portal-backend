<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Models\Bank;
use App\Repositories\BankRepository;
use Exception;

class BankController extends AppBaseController {
    public function __construct(
        private readonly BankRepository $repository,
    ) {
    }

    public function index() {
        return view('banks.index');
    }

    public function store(BankRequest $request) {
        try {
            $this->repository->create($request->all());
            return $this->sendSuccess(__('messages.flash.bank_Save'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Bank $bank) {
        return $this->sendResponse($bank, __('messages.flash.bank_retrieve'));
    }

    public function update(BankRequest $request, Bank $bank) {
        try {
            $this->repository->update($bank, $request->all());
            return $this->sendSuccess(__('messages.flash.bank_Save'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Bank $bank) {
        try {
            $this->repository->delete($bank);
            return $this->sendSuccess(__('messages.flash.bank_delete'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function changeStatus(Bank $bank) {
        $bank->is_active = !$bank->is_active;
        $bank->save();
        return $this->sendSuccess(__('messages.flash.status_update'));
    }
}
