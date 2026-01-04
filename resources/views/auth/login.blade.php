<form action="{{ route('redirect') }}" method="POST">
    @csrf
    @method('POST')
    <button type="submit">Se Connecter avec Google</button>
</form>
