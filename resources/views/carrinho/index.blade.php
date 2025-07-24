<tr>
    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
    <td colspan="2">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
</tr>
<tr>
    <td colspan="4" class="text-end"><strong>Frete:</strong></td>
    <td colspan="2">
        @if($frete == 0)
            <span class="text-success">Grátis</span>
        @else
            R$ {{ number_format($frete, 2, ',', '.') }}
        @endif
    </td>
</tr>
<tr>
    <td colspan="4" class="text-end"><strong>Total:</strong></td>
    <td colspan="2"><strong>R$ {{ number_format($total, 2, ',', '.') }}</strong></td>
</tr>
