<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-shadow2">
            {{ __('問題一覧') }}
        </h2>
    </x-slot> --}}
    @php
        $app_img="img/background01.jpg";
    @endphp
    <div class="">
        <div class="min-h-screen
    bg-no-repeat bg-cover bg-center"
    style="background-attachment: fixed;background-image:url('{{ asset($app_img) }}')">
            <div class="text-center pt-40">
            <a href="{{route('mobileQuizIndex')}}">

            <span class="text-blue-600 text-5xl font-bold text-center my-20 hover:text-blue-500 text-shadow1">モバイルクイズ</span>
        
            </a>
            </div>
            <div class="text-center my-20">
            <a href="{{route('netQuizIndex')}}">
            <span class="text-blue-600 text-5xl font-bold text-center my-20 hover:text-blue-500 text-shadow">ネットクイズ</span>
            </a>
            </div>
            <div class="text-center my-20">
            <a href="">
            <sapn class="text-blue-600 text-5xl font-bold text-center my-20 hover:text-blue-500 text-shadow">お気に入り</sapn>
            </a>
            </div>
        </div>
        
    </div>
</x-app-layout>
