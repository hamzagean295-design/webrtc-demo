<x-layouts.app>
    <h1>Votre consultations</h1>
    <table>
        <thead>
            <th>ID</th>
            <th>Patient</th>
            <th>Médecin</th>
            <th>Note</th>
        </thead>
        <tbody>
            @forelse($consultations as $consultation)
                <tr>
                    <td>{{ $consultation->id }}</td>
                    <td>{{ $consultation->patient->name }}</td>
                    <td>{{ $consultation->medecin->name }}</td>
                    <td>{{ $consultation->aiNote->content ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td>aucune consultation a été faite.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</x-layouts.app>
