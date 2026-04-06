<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Équipe</title>
</head>
<body>
    <form method="POST" action="{{ route('user.login.store') }}">
        @csrf

        @if ($errors->any())
            <div>{{ $errors->first('email') }}</div>
        @endif

        <input type="email" name="email" value="{{ old('email') }}" required>
        <input type="password" name="password" required>
        <input type="checkbox" name="remember"> Se souvenir de moi
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>