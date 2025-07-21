<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
      protected $fillable = ['pedido_id', 'status_recebido', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
