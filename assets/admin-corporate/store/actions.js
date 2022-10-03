import axios from 'axios';
import state from './state';

export default {
        getCompanyPhoneMask({ commit }) {
            axios.get(`get-company-phoneMask/${state.company.company_id}`).then(response => {
                commit('setCompanyPhoneMask', response.data);
            });
        },
};
