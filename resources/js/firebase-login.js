import { initializeApp } from 'firebase/app';
import { getAuth, GoogleAuthProvider, signInWithPopup } from 'firebase/auth';

const button = document.getElementById('firebase-login-button');
const form = document.getElementById('firebase-login-form');
const tokenInput = document.getElementById('firebase-id-token');
const errorBox = document.getElementById('firebase-login-error');
const helpBox = document.getElementById('firebase-login-help');
const spinner = document.getElementById('firebase-login-spinner');
const label = document.getElementById('firebase-login-label');

if (button && form && tokenInput) {
    const firebaseConfig = {
        apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
        authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
        projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
        appId: import.meta.env.VITE_FIREBASE_APP_ID,
        messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
        storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    };

    const missingKeys = Object.entries(firebaseConfig)
        .filter(([key, value]) => !value && ['apiKey', 'authDomain', 'projectId', 'appId'].includes(key))
        .map(([key]) => key);

    if (missingKeys.length > 0) {
        button.disabled = true;
        helpBox?.classList.remove('hidden');
        if (helpBox) {
            helpBox.textContent = `Lengkapi env ${missingKeys.join(', ')} untuk mengaktifkan login Firebase.`;
        }
    } else {
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });

        button.addEventListener('click', async (event) => {
            event.preventDefault();
            toggleLoading(true);
            errorBox?.classList.add('hidden');

            try {
                const { user } = await signInWithPopup(auth, provider);
                const idToken = await user.getIdToken(true);

                tokenInput.value = idToken;
                form.submit();
            } catch (error) {
                console.error('Login Firebase gagal:', error);
                if (errorBox) {
                    errorBox.textContent = 'Login Firebase gagal. Coba lagi atau cek konfigurasi Firebase.';
                    errorBox.classList.remove('hidden');
                }
            } finally {
                toggleLoading(false);
            }
        });
    }
}

function toggleLoading(isLoading) {
    if (!button) {
        return;
    }

    button.disabled = isLoading;
    spinner?.classList.toggle('hidden', !isLoading);
    label?.classList.toggle('hidden', isLoading);
}
