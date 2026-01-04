<x-layouts.guest>
    <form action="{{ route('redirect') }}" method="POST" class="mx-auto p-2 mt-8 text-center space-y-2">
        @csrf
        @method('POST')
        <button type="submit" class="flex items-center gap-2 mx-auto dark:bg-white dark:text-black bg-dark text:white rounded-sm p-2 hover:scale-[1.1] transition hover:font-medium ">
            <img src="{{ asset('google.svg') }}" width="20" >
            <span> Se Connecter avec Google </span>
        </button>
        <a href="{{ route('register') }}" class="text-blue-200 underline text-center my-2 font-medium hover:text-orange-600 transition">S'inscrire</a>
    </form>
</x-layouts.guest>
