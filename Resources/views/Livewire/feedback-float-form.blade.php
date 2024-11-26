<div class="position-fixed bottom-50 end-0 m-3" style="z-index: 9999">
    <!-- Floating Button -->
    <button class="btn btn-primary rounded-circle  text-center p-5"
         data-bs-toggle="modal" data-bs-target="#kt_modal_1_{{$uniqId}}"
                wire:ignore aria-label="Contact Support" >
        <i class="bi bi-chat-dots fs-1 p-0 fa-flip-horizontal" ></i>
    </button>

    <!-- Modal -->
    {{-- @if($showModal ?? false) --}}
    <div class=" modal fade" tabindex="-1" id="kt_modal_1_{{$uniqId}}"  wire:ignore.self>
        <div class="modal-dialog w-md-600px" wire:ignore.self>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Support</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session()->has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif
                    <div class="mb-3">
                        <span class="badge badge-outline badge-dark text-start p-2">
                           Tenant : {{$this->tenantName}} <br><br>
                           Route : {{$this->currentRoute}} <br><br>
                           feature : {{$this->feature}} <br><br>
                        </span>
                    </div>

                    <div class="mb-3">
                        <label for="testerName" class="form-label">Tester Name</label>
                        <input type="text" id="testerName" wire:model.lazy="testerName" class="form-control"
                            placeholder="Your name" />
                        @error('testerName') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" wire:model.lazy="description" class="form-control" rows="4"
                            placeholder="Describe your issue or suggestion..."></textarea>
                        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="storeFeedback" wire:loading.remove>Submit</button>
                    <button type="button" class="btn btn-primary" wire:click="storeFeedback" wire:loading wire:loading.disabled>Sumitting.....</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal-backdrop fade show"></div> --}}
    {{-- @endif --}}
</div>

@script
<script>
    let feedbackForm = document.getElementById('kt_modal_1_{{$uniqId}}');
    let feedbackFormModal = new bootstrap.Modal(feedbackForm);

    const testerName = localStorage.getItem('tester_name') || '';
        Livewire.hook('component.init', ({ component, cleanup }) => {
            Livewire.dispatch('set-tester', {testerName});
        })


        $wire.on('gettester', (data) => {
            feedbackFormModal.hide();
            localStorage.setItem('tester_name', data.tester_name);
            Swal.fire({
                text:"success",
                icon:'success'
            })
        });
</script>
@endscript
