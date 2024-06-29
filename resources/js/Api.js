export default class Api {

    static getMeta() {
       return axios.get('/meta');
    }

    static getSettings() {
        return axios.get('/settings');
    }

    static saveSettings(settings) {
        return axios.put('/settings', settings);
    }

    static deleteAccount() {
        return axios.delete('/account');
    }

}
