import { http } from "@/plugins/vuex-rest";
import Vue from "vue";

const SimpleAuth = (Vue) => {
  const state = Vue.observable({
    on: false,
    checking: true,
  });

  Vue.prototype.$auth = {
    login(data) {
      return http.post(`/auth/login`, data).then(({ data }) => {
        window.localStorage.setItem(`token`, data.token);

        // if (window.PasswordCredential) {
        //   const c = new window.PasswordCredential({
        //     id: data.email,
        //     password: data.password
        //   });

        //   navigator.credentials.store(c);
        // }

        return data;
      });
    },
    checkIn: () => {
      const token = window.localStorage.getItem(`token`);
      window.sessionStorage.setItem(`token`, token);

      if (token) {
        return http.post(`/auth/check-in`).then(({ data }) => {
          state.checking = false;
          state.on = true;
          return data;
        });
      } else {
        state.checking = false;
        state.on = false;

        return Promise.reject();
      }
    },
    logout: () =>
      http.post(`/auth/logout`).finally(() => {
        state.on = false;
        window.localStorage.removeItem(`token`);
      }),
    setStateProp(key, value) {
      state[key] = value;
    },
    get on() {
      return state.on;
    },
    get checking() {
      return state.checking;
    },
  };
};

Vue.use(SimpleAuth);
