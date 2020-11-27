<div id="{{ isset($id) ? $id : 'my-modal' }}" class="modal {{ isset($extra_classes) ? $extra_classes : '' }}">
    <div class="modal-background"></div>
    <div class="modal-content">
        {{ $slot }}
    </div>
    @if(isset($close))
        <button class="modal-close is-large" aria-label="close"></button>
    @endif
</div>

@section('modal_script')
    <script>
        var myModal = document.querySelector('{{ isset($id) ? '#' . $id : '#my-modal' }}');
        @if(isset($close))
            var modalCloseBtn = document.querySelector('{{ isset($id) ? '#' . $id : '#my-modal' }} button.modal-close');
            modalCloseBtn.addEventListener('click', function() {
                myModal.classList.remove('is-active');
            });
        @endif
    </script>
@endsection
