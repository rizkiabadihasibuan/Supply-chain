/**
 * API – Shared Fetch Wrapper
 * Digunakan oleh semua module untuk memanggil API endpoint
 */

const ApiClient = {
    baseUrl: window.location.origin,
    async get(path, params = {}) {
        const url = new URL(path, this.baseUrl);
        Object.keys(params).forEach(k => url.searchParams.append(k, params[k]));
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content } });
        if (!res.ok) throw new Error(res.statusText);
        return res.json();
    },
};
window.ApiClient = ApiClient;
