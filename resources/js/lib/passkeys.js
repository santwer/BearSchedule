const defaultRoutes = {
    registerOptions: '/api/user/passkeys/options',
    registerStore: '/api/user/passkeys',
    verifyOptions: '/passkeys/login/options',
    verifySubmit: '/passkeys/login',
};

const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');

    if (!meta) {
        return null;
    }

    const value = meta.getAttribute('content');

    return value ? { header: 'X-CSRF-TOKEN', value } : null;
};

const base64UrlToBuffer = (value) => {
    const padding = '='.repeat((4 - (value.length % 4)) % 4);
    const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
    const binary = atob(base64);
    const bytes = new Uint8Array(binary.length);

    for (let i = 0; i < binary.length; i++) {
        bytes[i] = binary.charCodeAt(i);
    }

    return bytes.buffer;
};

const bufferToBase64Url = (buffer) => {
    const bytes = new Uint8Array(buffer);
    let binary = '';

    for (let i = 0; i < bytes.length; i++) {
        binary += String.fromCharCode(bytes[i]);
    }

    return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
};

const decodeCreationOptions = (options) => ({
    ...options,
    challenge: base64UrlToBuffer(options.challenge),
    user: {
        ...options.user,
        id: base64UrlToBuffer(options.user.id),
    },
    excludeCredentials: options.excludeCredentials?.map((credential) => ({
        ...credential,
        id: base64UrlToBuffer(credential.id),
    })),
});

const decodeRequestOptions = (options) => ({
    ...options,
    challenge: base64UrlToBuffer(options.challenge),
    allowCredentials: options.allowCredentials?.map((credential) => ({
        ...credential,
        id: base64UrlToBuffer(credential.id),
    })),
});

const encodeCredential = (credential) => {
    const response = credential.response;

    return {
        id: credential.id,
        rawId: bufferToBase64Url(credential.rawId),
        type: credential.type,
        response: {
            clientDataJSON: bufferToBase64Url(response.clientDataJSON),
            attestationObject: response.attestationObject
                ? bufferToBase64Url(response.attestationObject)
                : undefined,
            authenticatorData: response.authenticatorData
                ? bufferToBase64Url(response.authenticatorData)
                : undefined,
            signature: response.signature
                ? bufferToBase64Url(response.signature)
                : undefined,
            userHandle: response.userHandle
                ? bufferToBase64Url(response.userHandle)
                : undefined,
        },
    };
};

const requestJson = async (url, method = 'GET', body = null) => {
    const csrf = getCsrfToken();
    const headers = {
        Accept: 'application/json',
    };

    if (method !== 'GET') {
        headers['Content-Type'] = 'application/json';
    }

    if (csrf) {
        headers[csrf.header] = csrf.value;
    }

    const response = await fetch(url, {
        method,
        headers,
        credentials: 'same-origin',
        body: body ? JSON.stringify(body) : undefined,
    });

    if (!response.ok) {
        let message = `Request failed with status ${response.status}`;

        try {
            const payload = await response.json();

            if (payload?.message) {
                message = payload.message;
            }
        } catch (error) {
            //
        }

        throw new Error(message);
    }

    return response.json();
};

const resolveRoutes = (options, defaults) => ({
    optionsRoute: options.routes?.options ?? defaults.options,
    submitRoute: options.routes?.submit ?? defaults.submit,
});

export class Passkeys {
    static isSupported() {
        return typeof window !== 'undefined'
            && typeof window.PublicKeyCredential !== 'undefined';
    }

    static async register(options) {
        if (!this.isSupported()) {
            throw new Error('Passkeys are not supported in this browser.');
        }

        const routes = resolveRoutes(options, {
            options: defaultRoutes.registerOptions,
            submit: defaultRoutes.registerStore,
        });

        const { options: creationOptions } = await requestJson(routes.optionsRoute);
        const credential = await navigator.credentials.create({
            publicKey: decodeCreationOptions(creationOptions),
        });

        return requestJson(routes.submitRoute, 'POST', {
            name: options.name,
            credential: encodeCredential(credential),
        });
    }

    static async verify(options = {}) {
        if (!this.isSupported()) {
            throw new Error('Passkeys are not supported in this browser.');
        }

        const routes = resolveRoutes(options, {
            options: defaultRoutes.verifyOptions,
            submit: defaultRoutes.verifySubmit,
        });

        const { options: requestOptions } = await requestJson(routes.optionsRoute);
        const credential = await navigator.credentials.get({
            publicKey: decodeRequestOptions(requestOptions),
        });

        return requestJson(routes.submitRoute, 'POST', {
            credential: encodeCredential(credential),
        });
    }
}
