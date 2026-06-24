const getXsrfToken = (): string => {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
};

export const fetchJson = async <T>(url: string): Promise<T> => {
    const response = await fetch(url, {
        headers: { Accept: 'application/json' },
    });

    if (!response.ok) {
        throw new Error(`Failed to fetch: ${response.status}`);
    }

    return response.json();
};

export const postJson = async <T>(
    url: string,
    body: Record<string, unknown> = {},
): Promise<T> => {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-XSRF-TOKEN': getXsrfToken(),
        },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return response.json();
};
