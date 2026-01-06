<x-layouts.app>
    <h1>List des patients</h1>
    <table>
       <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->email }}</td>
                    <td>
                        <a href="{{ route('start.consultation', ['userId' => $patient->id]) }}">Démarrer consulation</a>
                    </td>
                </tr>
            @empty
                <h1>aucun patient existe.</h1>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>
