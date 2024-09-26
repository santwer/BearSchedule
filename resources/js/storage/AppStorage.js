import {createStore} from "vuex";
import {v4 as uuid} from "uuid";
import Api from "@/Api.js";

export default createStore({
  state() {
    return {
        projects: [],
        user: {},
        meta: {},
        theme: 'light'
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
        }
    },
    getters: {
        projects(state) {
            return state.projects;
        },
        archivedProjects(state) {
            return [];
        },
        user(state) {
            return state.user;
        },
        meta(state) {
            return state.meta;
        },
        theme(state) {
            return state.theme;
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
