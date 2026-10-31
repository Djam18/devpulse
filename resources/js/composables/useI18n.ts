import { ref, computed } from 'vue';

export type Locale = 'fr' | 'en';

export const currentLocale = ref<Locale>('fr');

export function setLocale(locale: Locale): void {
    currentLocale.value = locale;
    if (typeof localStorage !== 'undefined') {
        localStorage.setItem('locale', locale);
    }
}

export function initLocale(): void {
    if (typeof localStorage !== 'undefined') {
        const saved = localStorage.getItem('locale') as Locale | null;
        if (saved && (saved === 'fr' || saved === 'en')) {
            currentLocale.value = saved;
        }
    }
}

export const messages = {
    fr: {
        welcome: {
            title: 'DevPulse · La suite de productivité pour développeurs & freelances',
            nav: {
                features: 'Fonctionnalités',
                workflow: 'Workflow',
                metrics: 'Métriques',
                architecture: 'Architecture',
                dashboard: 'Accéder au Dashboard',
                login: 'Connexion',
                register: 'Démarrer',
                apiDocs: 'Doc API',
            },
            hero: {
                tagline: 'DevPulse v1.0 · Conçu pour les développeurs & freelances exigeants',
                titleMain: 'Arrêtez de gaspiller votre temps de code dans des outils',
                titleHighlight: 'lents et surchargés.',
                subtitle: 'DevPulse unifie le Time Tracking en temps réel, un tableau Kanban réactif et le focus Pomodoro dans une interface fluide, ultra-rapide et sans friction.',
                startGithub: 'Démarrer avec GitHub',
                startEmail: 'Par e-mail',
            },
            metrics: {
                deepWork: 'Temps en Deep Work',
                latency: 'Latence des actions',
                friction: 'Clavier + 1-clic start',
                security: 'Souverain & Sécurisé',
            },
            features: {
                badge: 'Conçu par des développeurs, pour des développeurs',
                heading: 'Trois piliers pour libérer votre bande passante mentale',
                subheading: 'Fini les applications lourdes en Electron qui consomment 2 Go de RAM pour un simple chronomètre.',
                pillar1: {
                    title: 'Time Tracking Instantané',
                    desc: 'Lancez un chrono d’une frappe au clavier ou d’un clic sur n’importe quelle carte. La barre reste toujours accessible sans changer d’onglet.',
                    point1: 'Calcul au centième & facturation client',
                    point2: 'Détection automatique du projet actif',
                },
                pillar2: {
                    title: 'Kanban Sprint Réactif',
                    desc: 'Organisez votre backlog en 3 flux simples (To Do, In Progress, Done) avec priorités visuelles immédiates et filtre instantané par projet.',
                    point1: 'Mise à jour sans rechargement (Inertia + Pinia)',
                    point2: 'Déplacement direct d’une étape à l’autre',
                },
                pillar3: {
                    title: 'Pomodoro & Anti-Burnout',
                    desc: 'Découpez les gros chantiers techniques en sessions intenses de 25 minutes. Évaluez le coût réel en temps avant de livrer au client.',
                    point1: 'Compteur de tomates estimées vs réalisées',
                    point2: 'Protection contre le multitâche destructeur',
                },
            },
            architecture: {
                badge: 'Excellence technique',
                heading: 'Une architecture robuste taillée pour les projets critiques',
                description: 'DevPulse n’est pas un énième wrapper généré à la volée. Le projet respecte les plus hauts standards d’architecture logicielle : Single Responsibility, Actions réutilisables, DTO typés et tests automatisés.',
                item1Title: 'API REST v1 versionnée & documentée',
                item1Desc: 'Endpoints sécurisés par Laravel Sanctum avec documentation interactive OpenAPI / Swagger intégrée.',
                item2Title: 'Inertia.js & TypeScript de bout en bout',
                item2Desc: 'Expérience SPA sans réécriture d’API superflue, typage strict des contrats de données.',
                item3Title: 'Base PostgreSQL 17 optimisée',
                item3Desc: 'Indexation des clés étrangères, requêtes Eloquent protégées contre le piège N+1 et intégrité relationnelle stricte.',
            },
            cta: {
                badge: 'Installation en 30 secondes chrono',
                heading: 'Prêt à maximiser votre productivité de dev ?',
                description: 'Rejoignez une suite conçue spécifiquement pour vous faire gagner des heures de concentration chaque semaine.',
                freeAccount: 'Créer mon compte gratuit',
                login: 'Se connecter',
            },
            footer: {
                copy: '© 2026 DevPulse · Suite de Productivité pour Développeurs & Freelances. Construit avec Laravel 11, Vue 3 & PostgreSQL.',
            },
        },
        auth: {
            login: {
                title: 'Connexion · DevPulse',
                welcome: 'Bienvenue sur DevPulse',
                subtitle: 'Connectez-vous pour reprendre votre session de code.',
                githubButton: 'Continuer avec GitHub',
                divider: 'ou avec votre e-mail',
                emailLabel: 'Adresse e-mail',
                emailPlaceholder: 'vous@devpulse.io',
                passwordLabel: 'Mot de passe',
                rememberMe: 'Se souvenir de moi',
                forgotPassword: 'Mot de passe oublié ?',
                submitButton: 'Se connecter',
                noAccount: 'Pas encore de compte ?',
                registerLink: 'Créer un compte gratuit',
            },
            register: {
                title: 'Créer un compte · DevPulse',
                heading: 'Créer votre compte',
                subtitle: 'Rejoignez la suite de productivité pour développeurs & freelances.',
                githubButton: "S'inscrire avec GitHub",
                divider: 'ou avec votre e-mail',
                nameLabel: 'Nom complet',
                namePlaceholder: 'Ada Lovelace',
                emailLabel: 'Adresse e-mail',
                emailPlaceholder: 'ada@devpulse.io',
                passwordLabel: 'Mot de passe',
                passwordConfirmationLabel: 'Confirmer le mot de passe',
                submitButton: 'Créer mon compte',
                alreadyRegistered: 'Déjà inscrit ?',
                loginLink: 'Se connecter',
            },
            forgotPassword: {
                title: 'Mot de passe oublié · DevPulse',
                heading: 'Réinitialisation de mot de passe',
                description: 'Mot de passe oublié ? Aucun problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.',
                emailLabel: 'Adresse e-mail',
                submitButton: 'Envoyer le lien de réinitialisation',
                backToLogin: 'Retour à la connexion',
            },
            resetPassword: {
                title: 'Nouveau mot de passe · DevPulse',
                heading: 'Choisissez un nouveau mot de passe',
                emailLabel: 'Adresse e-mail',
                passwordLabel: 'Nouveau mot de passe',
                passwordConfirmationLabel: 'Confirmer le nouveau mot de passe',
                submitButton: 'Mettre à jour mon mot de passe',
            },
            confirmPassword: {
                title: 'Confirmation du mot de passe · DevPulse',
                heading: 'Zone sécurisée',
                description: 'Cette zone de l’application est sécurisée. Veuillez confirmer votre mot de passe avant de continuer.',
                passwordLabel: 'Mot de passe',
                submitButton: 'Confirmer',
            },
            verifyEmail: {
                title: 'Vérification e-mail · DevPulse',
                heading: 'Vérifiez votre adresse e-mail',
                description: 'Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ?',
                linkSent: 'Un nouveau lien de vérification a été envoyé à l’adresse e-mail fournie lors de l’inscription.',
                resendButton: 'Renvoyer l’e-mail de vérification',
                logoutButton: 'Se déconnecter',
            },
        },
        profile: {
            title: 'Profil · DevPulse',
            header: 'Profil & Paramètres',
            info: {
                heading: 'Informations du profil',
                description: 'Mettez à jour les informations de profil et l’adresse e-mail de votre compte.',
                nameLabel: 'Nom complet',
                emailLabel: 'Adresse e-mail',
                unverifiedEmail: 'Votre adresse e-mail n’est pas vérifiée.',
                resendVerification: 'Cliquez ici pour renvoyer l’e-mail de vérification.',
                verificationSent: 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail.',
                saveButton: 'Enregistrer les modifications',
                saved: 'Enregistré.',
            },
            password: {
                heading: 'Modifier le mot de passe',
                description: 'Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.',
                currentPasswordLabel: 'Mot de passe actuel',
                newPasswordLabel: 'Nouveau mot de passe',
                confirmPasswordLabel: 'Confirmer le mot de passe',
                saveButton: 'Enregistrer le mot de passe',
                saved: 'Enregistré.',
            },
            delete: {
                heading: 'Supprimer le compte',
                description: 'Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez sauvegarder les données que vous souhaitez conserver avant de continuer.',
                deleteButton: 'Supprimer mon compte',
                modalTitle: 'Êtes-vous sûr de vouloir supprimer votre compte ?',
                modalDescription: 'Une fois votre compte supprimé, toutes ses données seront définitivement perdues. Veuillez entrer votre mot de passe pour confirmer la suppression définitive.',
                passwordPlaceholder: 'Mot de passe de confirmation',
                cancelButton: 'Annuler',
                confirmDeleteButton: 'Confirmer la suppression',
            },
        },
        tasks: {
            title: 'Tâches & Kanban · DevPulse',
            header: 'Tâches & Kanban',
            badge: 'Tableau de Sprint',
            subtitle: 'Priorisez votre dette technique, jalons de fonctionnalités et suivez vos sessions en direct.',
            newTaskButton: 'Nouvelle tâche',
            kpi: {
                total: 'Total des tâches',
                inProgress: 'En cours',
                done: 'Terminées',
                completionRate: 'Taux d’achèvement',
            },
            filter: {
                searchPlaceholder: 'Rechercher par titre ou description...',
                allProjects: 'Tous les projets',
            },
            columns: {
                todo: 'À faire',
                inProgress: 'En cours',
                done: 'Terminé',
                empty: 'Aucune tâche dans',
            },
            deleteConfirm: 'Êtes-vous sûr de vouloir supprimer cette tâche ?',
        },
        dashboard: {
            title: 'Tableau de bord · DevPulse',
            header: 'Espace Développeur',
            subtitle: 'Optimisez vos sessions de travail profond et vos livrables clients actifs.',
            goToKanban: 'Aller au Kanban',
            banner: {
                badge: 'Sprint Actif',
                greeting: 'Prêt à livrer, {name} ?',
                activeTasksCount: 'Vous avez {count} tâche active en cours. | Vous avez {count} tâches actives en cours.',
                focusTip: 'Concentrez-vous sur un jalon à fort impact à la fois.',
            },
        },
    },
    en: {
        welcome: {
            title: 'DevPulse · The productivity suite for developers & freelancers',
            nav: {
                features: 'Features',
                workflow: 'Workflow',
                metrics: 'Metrics',
                architecture: 'Architecture',
                dashboard: 'Access Dashboard',
                login: 'Sign In',
                register: 'Get Started',
                apiDocs: 'API Docs',
            },
            hero: {
                tagline: 'DevPulse v1.0 · Designed for demanding developers & freelancers',
                titleMain: 'Stop wasting your coding time in tools that are',
                titleHighlight: 'slow and cluttered.',
                subtitle: 'DevPulse brings together real-time Time Tracking, a reactive Kanban board, and Pomodoro focus in a fast, seamless interface.',
                startGithub: 'Start with GitHub',
                startEmail: 'With Email',
            },
            metrics: {
                deepWork: 'Deep Work Time',
                latency: 'Action Latency',
                friction: 'Keyboard + 1-click start',
                security: 'Sovereign & Secure',
            },
            features: {
                badge: 'Engineered by developers, for developers',
                heading: 'Three pillars to free your mental bandwidth',
                subheading: 'No more bloated Electron applications consuming 2 GB of RAM for a simple timer.',
                pillar1: {
                    title: 'Instant Time Tracking',
                    desc: 'Start a timer with a keystroke or click on any card. The bar stays accessible without switching tabs.',
                    point1: 'Hundredth-of-a-second precision & invoicing',
                    point2: 'Automatic active project detection',
                },
                pillar2: {
                    title: 'Reactive Sprint Kanban',
                    desc: 'Organize your backlog into 3 simple streams (To Do, In Progress, Done) with visual priorities and project filtering.',
                    point1: 'Zero-reload updates (Inertia + Pinia)',
                    point2: 'Direct one-step move across lanes',
                },
                pillar3: {
                    title: 'Pomodoro & Anti-Burnout',
                    desc: 'Break big technical challenges into 25-minute focus intervals. Measure actual cost in time before shipping to clients.',
                    point1: 'Estimated vs actual tomato counter',
                    point2: 'Protection against destructive multitasking',
                },
            },
            architecture: {
                badge: 'Technical Excellence',
                heading: 'A robust architecture built for critical workflows',
                description: 'DevPulse is not just another wrapper. The codebase follows modern software architecture patterns: Single Responsibility, reusable Actions, typed DTOs, and automated testing.',
                item1Title: 'Versioned & Documented REST API v1',
                item1Desc: 'Endpoints secured by Laravel Sanctum with interactive OpenAPI / Swagger docs included.',
                item2Title: 'End-to-End Inertia.js & TypeScript',
                item2Desc: 'Fast SPA experience without duplicating API routing, backed by strict data contracts.',
                item3Title: 'Optimized PostgreSQL 17 Database',
                item3Desc: 'Indexed foreign keys, Eloquent queries protected against N+1 traps, and strict relational integrity.',
            },
            cta: {
                badge: '30-second quick setup',
                heading: 'Ready to elevate your developer productivity?',
                description: 'Join a suite purpose-built to help you gain hours of deep focus each week.',
                freeAccount: 'Create my free account',
                login: 'Sign In',
            },
            footer: {
                copy: '© 2026 DevPulse · Productivity Suite for Developers & Freelancers. Built with Laravel 11, Vue 3 & PostgreSQL.',
            },
        },
        auth: {
            login: {
                title: 'Log In · DevPulse',
                welcome: 'Welcome to DevPulse',
                subtitle: 'Sign in to resume your coding session.',
                githubButton: 'Continue with GitHub',
                divider: 'or with your email',
                emailLabel: 'Email address',
                emailPlaceholder: 'you@devpulse.io',
                passwordLabel: 'Password',
                rememberMe: 'Remember me',
                forgotPassword: 'Forgot password?',
                submitButton: 'Sign In',
                noAccount: "Don't have an account?",
                registerLink: 'Create a free account',
            },
            register: {
                title: 'Create an Account · DevPulse',
                heading: 'Create your account',
                subtitle: 'Join the developer and freelance productivity suite.',
                githubButton: 'Sign up with GitHub',
                divider: 'or with your email',
                nameLabel: 'Full name',
                namePlaceholder: 'Ada Lovelace',
                emailLabel: 'Email address',
                emailPlaceholder: 'ada@devpulse.io',
                passwordLabel: 'Password',
                passwordConfirmationLabel: 'Confirm Password',
                submitButton: 'Create my account',
                alreadyRegistered: 'Already registered?',
                loginLink: 'Sign in',
            },
            forgotPassword: {
                title: 'Forgot Password · DevPulse',
                heading: 'Reset your password',
                description: 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.',
                emailLabel: 'Email address',
                submitButton: 'Email Password Reset Link',
                backToLogin: 'Back to sign in',
            },
            resetPassword: {
                title: 'Reset Password · DevPulse',
                heading: 'Choose a new password',
                emailLabel: 'Email address',
                passwordLabel: 'New password',
                passwordConfirmationLabel: 'Confirm new password',
                submitButton: 'Reset Password',
            },
            confirmPassword: {
                title: 'Confirm Password · DevPulse',
                heading: 'Secure area',
                description: 'This is a secure area of the application. Please confirm your password before continuing.',
                passwordLabel: 'Password',
                submitButton: 'Confirm',
            },
            verifyEmail: {
                title: 'Email Verification · DevPulse',
                heading: 'Verify your email address',
                description: 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?',
                linkSent: 'A new verification link has been sent to the email address you provided during registration.',
                resendButton: 'Resend Verification Email',
                logoutButton: 'Log Out',
            },
        },
        profile: {
            title: 'Profile · DevPulse',
            header: 'Profile & Settings',
            info: {
                heading: 'Profile Information',
                description: "Update your account's profile information and email address.",
                nameLabel: 'Full Name',
                emailLabel: 'Email Address',
                unverifiedEmail: 'Your email address is unverified.',
                resendVerification: 'Click here to re-send the verification email.',
                verificationSent: 'A new verification link has been sent to your email address.',
                saveButton: 'Save Changes',
                saved: 'Saved.',
            },
            password: {
                heading: 'Update Password',
                description: 'Ensure your account is using a long, random password to stay secure.',
                currentPasswordLabel: 'Current Password',
                newPasswordLabel: 'New Password',
                confirmPasswordLabel: 'Confirm Password',
                saveButton: 'Save Password',
                saved: 'Saved.',
            },
            delete: {
                heading: 'Delete Account',
                description: 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data you wish to retain.',
                deleteButton: 'Delete Account',
                modalTitle: 'Are you sure you want to delete your account?',
                modalDescription: 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.',
                passwordPlaceholder: 'Confirmation Password',
                cancelButton: 'Cancel',
                confirmDeleteButton: 'Delete Account',
            },
        },
        tasks: {
            title: 'Tasks & Kanban · DevPulse',
            header: 'Tasks & Kanban',
            badge: 'Sprint Board',
            subtitle: 'Prioritize daily technical debt, feature milestones, and track live session times.',
            newTaskButton: 'New Task',
            kpi: {
                total: 'Total Items',
                inProgress: 'In Progress',
                done: 'Done',
                completionRate: 'Completion Rate',
            },
            filter: {
                searchPlaceholder: 'Search tasks by title or description...',
                allProjects: 'All Projects',
            },
            columns: {
                todo: 'To Do',
                inProgress: 'In Progress',
                done: 'Done',
                empty: 'No tasks in',
            },
            deleteConfirm: 'Are you sure you want to delete this task?',
        },
        dashboard: {
            title: 'Developer Hub · DevPulse',
            header: 'Developer Hub',
            subtitle: 'Streamline deep work sprints and active client deliverables.',
            goToKanban: 'Go to Kanban',
            banner: {
                badge: 'Sprint Velocity Active',
                greeting: 'Ready to ship, {name}?',
                activeTasksCount: 'You have {count} active task in progress. | You have {count} active tasks in progress.',
                focusTip: 'Focus on one high-impact milestone at a time.',
            },
        },
    },
};

export function useI18n() {
    const t = (path: string): string => {
        const keys = path.split('.');
        let current: any = messages[currentLocale.value];

        for (const key of keys) {
            if (current && typeof current === 'object' && key in current) {
                current = current[key];
            } else {
                let fallback: any = messages.fr;
                for (const fbKey of keys) {
                    if (fallback && typeof fallback === 'object' && fbKey in fallback) {
                        fallback = fallback[fbKey];
                    } else {
                        return path;
                    }
                }
                return typeof fallback === 'string' ? fallback : path;
            }
        }

        return typeof current === 'string' ? current : path;
    };

    return {
        locale: currentLocale,
        setLocale,
        t,
    };
}
