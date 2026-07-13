<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte étudiant - Success Business Training</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem 1.5rem;
            box-sizing: border-box;
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 22%),
                linear-gradient(180deg, var(--bg), #f8f3ec 55%, #f2ebe2 100%);
        }

        .auth-card {
            background: var(--surface-strong);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: var(--shadow);
            box-sizing: border-box;
            transition: var(--transition);
        }
        
        .auth-card:hover {
            box-shadow: var(--shadow-strong);
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 2.5rem;
            text-align: left;
            text-decoration: none;
            color: inherit;
        }

        .auth-logo .brand-mark {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand), #18a39a);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
            font-size: 0.95rem;
        }

        .auth-logo-text strong {
            display: block;
            font-size: 0.95rem;
            color: var(--text);
            line-height: 1.25;
        }

        .auth-logo-text small {
            color: var(--muted);
            font-size: 0.75rem;
            display: block;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-align: center;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .auth-subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            text-align: center;
            margin-bottom: 2.5rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1.15rem;
            border-radius: 0.75rem;
            border: 1px solid var(--line);
            background: var(--surface);
            font-family: inherit;
            color: var(--text);
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand);
            background: var(--surface-strong);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.15);
        }

        .btn-auth {
            width: 100%;
            background: linear-gradient(135deg, var(--brand), #18a39a);
            color: #ffffff;
            border: none;
            padding: 1rem;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(15, 118, 110, 0.2);
            transition: var(--transition);
            margin-top: 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.3);
        }

        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
        }

        .auth-footer a:hover {
            color: var(--brand-dark);
            text-decoration: underline;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 1.25rem;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <a class="auth-logo" href="{{ url('/') }}">
            <span class="brand-mark">SB</span>
            <div class="auth-logo-text">
                <strong>Success Business Training</strong>
                <small>IA, Business et Marketing</small>
            </div>
        </a>

        <h1 class="auth-title">Créer votre compte</h1>
        <p class="auth-subtitle">Rejoignez notre espace d'apprentissage en ligne.</p>

        @if($errors->any())
            <div class="alert-error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('student.signup.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nom complet *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="ex: Jean Dupont">
            </div>

            <div class="form-group">
                <label for="email">Adresse email *</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="ex: jean.dupont@email.com">
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone *</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="ex: +225 07080910">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Minimum 6 caractères" style="padding-right: 3rem;">
                    <button type="button" id="togglePassword" style="position: absolute; right: 1rem; background: none; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; padding: 0;">
                        <svg id="eyeShowPass" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg id="eyeHidePass" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe *</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Confirmez votre mot de passe" style="padding-right: 3rem;">
                    <button type="button" id="togglePasswordConf" style="position: absolute; right: 1rem; background: none; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; padding: 0;">
                        <svg id="eyeShowConf" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg id="eyeHideConf" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth">Créer mon compte étudiant</button>
        </form>

        <div class="auth-footer">
            Vous avez déjà un compte ? <a href="{{ route('student.login') }}">Connectez-vous</a>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeShow = document.getElementById('eyeShowPass');
            const eyeHide = document.getElementById('eyeHidePass');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeShow.style.display = 'none';
                eyeHide.style.display = 'inline';
            } else {
                passwordInput.type = 'password';
                eyeShow.style.display = 'inline';
                eyeHide.style.display = 'none';
            }
        });

        document.getElementById('togglePasswordConf').addEventListener('click', function () {
            const passwordInput = document.getElementById('password_confirmation');
            const eyeShow = document.getElementById('eyeShowConf');
            const eyeHide = document.getElementById('eyeHideConf');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeShow.style.display = 'none';
                eyeHide.style.display = 'inline';
            } else {
                passwordInput.type = 'password';
                eyeShow.style.display = 'inline';
                eyeHide.style.display = 'none';
            }
        });
    </script>
</body>
</html>
