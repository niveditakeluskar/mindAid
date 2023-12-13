// store.js
import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    // Your state properties
  },
  mutations: {
    // Your mutations
  },
  actions: {
    fetchPatientModules(context, patientId) {
      return axios({
        method: "GET",
        url: `/patients/patient-module/${patientId}/patient-module`,
      });
    },
    // Other actions...
  },
});
