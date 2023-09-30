<button {{$attributes->merge(['class' => 'btn mb-2'])}} wire:loading.attr="disabled">
    {{$slot}}
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
          {{$attributes}}  wire:loading></span>

</button>
