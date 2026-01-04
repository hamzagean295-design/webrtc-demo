<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LaraConsult</title>
    </head>
    <body>
        <h1>Bienvenue sur LaraConsult</h1>
        <p>Une app pour conslutations médecales en ligne Médecin-Patient Powered By AI for real-time taking notes</p>
        <form action="{{ route('redirect') }}" method="POST">
            @csrf
            @method('POST')
            <div>
                <div>
                    <label for="patient">Je suis patient</label>
                    <input type="radio" id="patient" name="role" value="patient" required>
                </div>
                <div>
                    <label for="medecin">Je suis médecin</label>
                    <input type="radio" id="medecin" name="role" value="medecin" required>
                </div>
            </div>
            <button type="submit">s'incrire</button>
        </form>
    </body>
</html>
