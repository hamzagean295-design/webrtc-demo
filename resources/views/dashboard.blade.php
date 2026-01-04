<div>
    <h1>Welcome to your dashboard {{ auth()->user()->role }}</h1>
    <form action="{{ route('logout') }}" method="POST">
        @method('DELETE')
        @csrf
        <button type="submit">logout</button>
    </form>
</div>
