<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès refusé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-shell">
    <div class="auth-card">
        <p class="auth-eyebrow">Erreur 403</p>
        <h1>Accès refusé</h1>
        <p class="auth-subtitle">Vous n'avez pas accès à cette page.</p>
        <a href="{{ route('dashboard') }}" class="auth-submit" style="display:inline-block;text-decoration:none;text-align:center;">
            Retour au tableau de bord
        </a>
    </div>
</body>
</html>
