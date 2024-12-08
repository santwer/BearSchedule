import {createStore} from "vuex";
import {v4 as uuid} from "uuid";
import Api from "@/Api.js";

export default createStore({
  state() {
    return {
        projects: [],
        user: {},
        meta: {},
        theme: 'light',
        isLoading: false
    }
  },
    mutations: {
        setProjects(state, projects) {
            state.projects = projects;
        },
        setUser(state, user) {
            state.user = user;
        },
        setMeta(state, meta) {
            state.meta = meta;
        },
        setTheme(state, theme) {
            state.theme = theme;
        },
        setLoading(state, isLoading) {
            state.isLoading = isLoading;
        }
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        projects(state) {
            return state.projects.filter(project => !project.is_archived);
        },
        archivedProjects(state) {
            return state.projects.filter(project => project.is_archived);
        },
        user(state) {
            return state.user;
        },
        meta(state) {
            return state.meta;
        },
        theme(state) {
            return state.theme;
        },
        projectById: (state) => (id) => {
            return state.projects.find(project => project.id === id || project.id === parseInt(id));
        }
    },
    actions: {
        getMeta({commit}) {

            return Api.getMeta().then((response) => {
                commit('setProjects', response.data.projects);
                commit('setUser', response.data.user);
                commit('setMeta', response.data.meta);
            });
        }
    }
})
