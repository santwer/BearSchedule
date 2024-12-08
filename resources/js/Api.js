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

    static getTimeline(id) {
        return axios.get('/timeline/' + id,);
    }

    static getProjectSettings(id) {
        return axios.get('/timeline/' + id + '/settings',);
    }

    static setProjectSetting(id, setting, value) {
        return axios.post('/timeline/' + id + '/settings', {
            setting: setting,
            value: value

        });
    }

    static setItem(item) {
        return axios.post('/items', item);
    }

    static deleteItem(id) {
        return axios.delete('/item/' + id);
    }

    static setGroup(group) {
        return axios.post('/groups/', group);
    }

    static getShareData(id) {
        return axios.get('/share/' + id);
    }

    static searchPerson(id, query) {
        return axios.get('/search-person/' + id + '/', {
            params: {
                query: query
            }
        });
    }

    static changeRole(project, user, role) {
        return axios.post('/role', {project: project, user: user, role: role});
    }

    static removePerson(project, user) {
        return axios.delete('/role', {params: {project: project, user: user}});
    }
}
