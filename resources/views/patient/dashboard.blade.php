<x-layouts.app>
    <h1>List des médecins</h1>
    <table>
       <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @forelse($medecins as $medecin)
                <tr>
                    <td>{{ $medecin->name }}</td>
                    <td>{{ $medecin->email }}</td>
                    <td>
                        <a href="{{ route('start.consultation', ['userId' => $medecin->id]) }}">Démarrer consulation</a>
                    </td>
                </tr>
            @empty
                <h1>aucun médecin existe.</h1>
            @endforelse
        </tbody>
    </table>
    <div id="container">
    </div>
</x-layouts.app>
