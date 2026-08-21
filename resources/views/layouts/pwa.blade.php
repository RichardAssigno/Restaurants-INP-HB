<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<link rel="apple-touch-icon" href="{{ asset('pwa/icon-192.png') }}">
<meta name="theme-color" content="#0b946f">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Restaurant">

<style>
    .pwa-install-banner {
        align-items: center;
        background: #e8f7ed;
        border: 1px solid #b9e6c7;
        border-radius: .55rem;
        box-shadow: 0 5px 18px rgba(27, 104, 59, .18);
        color: #216e3a;
        display: none;
        gap: .55rem;
        left: 50%;
        max-width: calc(100vw - 2rem);
        padding: .65rem .75rem;
        position: fixed;
        top: 1rem;
        transform: translateX(-50%);
        z-index: 10050;
    }

    .pwa-install-banner.is-visible {
        display: flex;
    }

    .pwa-install-banner__icon {
        align-items: center;
        background: #ccefd7;
        border-radius: 50%;
        display: inline-flex;
        flex: 0 0 28px;
        height: 28px;
        justify-content: center;
    }

    .pwa-install-banner__link {
        color: #18733a;
        cursor: pointer;
        font-size: .84rem;
        font-weight: 700;
        line-height: 1.25;
        text-decoration: underline;
    }

    .pwa-install-banner__link:hover,
    .pwa-install-banner__link:focus {
        color: #0e5529;
    }

    .pwa-install-banner__close {
        align-items: center;
        background: transparent;
        border: 0;
        color: #4b805d;
        cursor: pointer;
        display: inline-flex;
        font-size: 1.1rem;
        height: 28px;
        justify-content: center;
        line-height: 1;
        margin-left: .15rem;
        padding: 0;
        width: 28px;
    }

    @media (max-width: 575.98px) {
        .pwa-install-banner {
            max-width: calc(100vw - 1.3rem);
            top: .65rem;
        }
    }
</style>

<script>
    (function () {
        const cleFermeture = 'sgi-pwa-install-fermee';
        let invitationInstallation = null;
        let banniere = null;

        function estDejaInstallee() {
            return window.matchMedia('(display-mode: standalone)').matches
                || window.navigator.standalone === true;
        }

        function estIOS() {
            return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        }

        function masquerBanniere() {
            if (banniere) {
                banniere.classList.remove('is-visible');
            }
        }

        function afficherBanniere() {
            if (!banniere || estDejaInstallee() || sessionStorage.getItem(cleFermeture) === '1') {
                return;
            }

            banniere.classList.add('is-visible');
        }

        function creerBanniere() {
            if (document.getElementById('pwa-install-banner')) {
                banniere = document.getElementById('pwa-install-banner');
                return;
            }

            banniere = document.createElement('aside');
            banniere.id = 'pwa-install-banner';
            banniere.className = 'pwa-install-banner';
            banniere.setAttribute('role', 'status');
            banniere.setAttribute('aria-live', 'polite');
            banniere.innerHTML = [
                '<span class="pwa-install-banner__icon" aria-hidden="true">&#8595;</span>',
                '<a class="pwa-install-banner__link" href="#">Installer l\'application</a>',
                '<button class="pwa-install-banner__close" type="button" aria-label="Fermer le message d\'installation">&times;</button>'
            ].join('');

            document.body.appendChild(banniere);

            banniere.querySelector('.pwa-install-banner__close').addEventListener('click', function () {
                sessionStorage.setItem(cleFermeture, '1');
                masquerBanniere();
            });

            banniere.querySelector('.pwa-install-banner__link').addEventListener('click', async function (event) {
                event.preventDefault();

                if (invitationInstallation) {
                    invitationInstallation.prompt();
                    const choix = await invitationInstallation.userChoice;
                    invitationInstallation = null;

                    if (choix.outcome === 'accepted') {
                        masquerBanniere();
                    }

                    return;
                }

                if (estIOS()) {
                    window.alert("Sur iPhone ou iPad : touchez Partager, puis « Ajouter à l’écran d’accueil ».");
                    return;
                }

                window.alert("Ouvrez le menu de votre navigateur puis choisissez « Installer l’application » ou « Ajouter à l’écran d’accueil ».");
            });
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register(@json(asset('sw.js')), { scope: '/' })
                    .catch(function (erreur) {
                        console.warn('Le service worker PWA n\'a pas pu être enregistré.', erreur);
                    });
            });
        }

        window.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            invitationInstallation = event;
            afficherBanniere();
        });

        window.addEventListener('appinstalled', function () {
            invitationInstallation = null;
            masquerBanniere();
        });

        document.addEventListener('DOMContentLoaded', function () {
            creerBanniere();

            if (!estDejaInstallee()) {
                afficherBanniere();
            }
        });
    })();
</script>
