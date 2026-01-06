<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold dark:text-white">Détail de la Note (Consultation #{{ $note->consultation_id }})</h1>
                <a href="{{ url()->previous() }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    &larr; Retour
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Transcription de l'IA :</h2>
                    {{-- La balise <pre> préserve les sauts de ligne et le formatage du texte --}}
                    <pre class="whitespace-pre-wrap font-sans text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $note->content ?? 'Aucun contenu trouvé pour cette note.' }}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
