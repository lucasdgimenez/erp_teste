<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'subtotal', 'frete', 'desconto', 'total',
        'status', 'cep', 'endereco', 'email_cliente', 'cupom_id', 'user_id', 'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }
}
