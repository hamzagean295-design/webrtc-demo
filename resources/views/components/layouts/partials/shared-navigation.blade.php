<nav>
    <ul>
        <li><a href="{{ route(auth()->user()->role . '.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('consultation.index') }}">Consultations</a></li>
    </ul>
</nav>
