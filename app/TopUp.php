<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopUp extends Model
{
    public function send($request, $path, $wallet_id)
    {
        $model              = new $this;
        $model->wallet_id   = $wallet_id;
        $model->request_by  = auth()->user()->id;
        $model->approved_by = null;
        $model->receipt     = $path;
        $model->amount      = $request->amount;
        $model->status      = 'pending';
        $model->save();
    }

    public function approver()
    {
        return $this::hasOne('App\User', 'id', 'approved_by');
    }

    public function requestor()
    {
        return $this::hasOne('App\User', 'id', 'request_by');
    }

    public static function countPending()
    {
        return (new static())->newQuery()->where('status', 'pending')->count();
    }

    public function updateStatus($request)
    {
        $model              = $this::find($request->id);
        $model->status      = $request->status;
        $model->approved_by = auth()->id();
        $model->save();
    }

    public function isStatusApproved($id)
    {
        return $this::newQuery()->where('id', $id)->where('status', 'approved')->count() == 1;
    }

    public function isStatusDenied($id)
    {
        return $this::newQuery()->where('id', $id)->where('status', 'denied')->count() == 1;
    }

    public function getRequester($id)
    {
        return $this::newQuery()->where('id', $id)->pluck('request_by')[0];
    }

    public function getAmount($id)
    {
        return $this::newQuery()->where('id', $id)->pluck('amount')[0];
    }
}
