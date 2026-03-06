<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-shell">
    <div class="auth-card">
        <p class="auth-eyebrow">AutoFlow</p>
        <h1>Connexion</h1>
        <p class="auth-subtitle">Entrez vos identifiants pour acceder au dashboard.</p>

        <form method="POST" action="{{ route('login.attempt') }}" class="auth-form">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required>

            <label class="remember-row">
                <input type="checkbox" name="remember">
                <span>Se souvenir de moi</span>
            </label>

            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="auth-submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
