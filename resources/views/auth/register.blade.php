<x-layouts.guest>
    <main class="min-h-screen flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="w-full max-w-lg p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">

            <!-- En-tête -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">Bienvenue sur LaraConsult</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    Une app pour consultations médicales en ligne Médecin-Patient
                    <span class="block mt-1 font-medium text-purple-500 italic">Powered By AI for real-time taking notes</span>
                </p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('redirect') }}" method="POST" class="space-y-6">
                @csrf
                @method('POST')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Option Patient -->
                        <label for="patient" class="relative flex flex-col items-center justify-center p-6 border-2 rounded-xl cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-700 @error('role') border-red-500 dark:border-red-500 @else border-gray-200 dark:border-gray-600 @enderror has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/30 transition-all">
                            <span class="text-3xl mb-2 text-gray-800 dark:text-gray-100">👤</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Je suis patient</span>
                            <input type="radio" id="patient" name="role" value="patient" {{ old('role') == 'patient' ? 'checked' : '' }} class="mt-3 w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-500" required>
                        </label>

                        <!-- Option Médecin -->
                        <label for="medecin" class="relative flex flex-col items-center justify-center p-6 border-2 rounded-xl cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-700 @error('role') border-red-500 dark:border-red-500 @else border-gray-200 dark:border-gray-600 @enderror has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/30 transition-all">
                            <span class="text-3xl mb-2 text-gray-800 dark:text-gray-100">🩺</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Je suis médecin</span>
                            <input type="radio" id="medecin" name="role" value="medecin" {{ old('role') == 'medecin' ? 'checked' : '' }} class="mt-3 w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-500" required>
                        </label>
                    </div>

                    <!-- Affichage de l'erreur pour le champ 'role' -->
                    @error('role')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2 font-medium flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                    S'inscrire sur la plateforme
                </button>
            </form>

        </div>
    </main>
</x-layouts.guest>
