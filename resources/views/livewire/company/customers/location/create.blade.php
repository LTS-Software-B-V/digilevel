<div class="container-fluid">
    <div class="page-header  my-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-header-title">
                    Liften
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm  btn-120" wire:click="save()">
                    Opslaan
                </button>
                <button type="button" onclick="history.back()" class="btn btn-secondary btn-sm  ">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-header card-header-content-md-between bg-light">
                    Relatie

                </div>
                <div class="card-body">
                    @livewire('company.customers.partials.information', ['customer_id' => $customer_id])
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header card-header-content-md-between  ">

                    Gegevens
                </div>

                <div class="card-body">
ss
                </div>
            </div>
        </div>

    </div>
</div>
 