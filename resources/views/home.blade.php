<x-layouts.main-layout pageTitle="home">

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col p-3">
                <p class="text-center my-5 display-6">Bem vido {{Auth::user()->username}} a pagina Home</p>
            </div>
        </div>
    </div>
</x-layouts.main-layout>
