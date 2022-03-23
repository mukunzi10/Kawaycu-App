import Vuex from 'vuex'
import Vue from 'vue';
Vue.use(Vuex);

export const stores= new Vuex.Store({
    state:{
        user:{},
        authenticated:false
    },
    mutations:{
        logedIn(state,payload){
          state.user=payload;
          state.authenticated=true;
        },
        logout(state){
            state.authenticated=false;
            state.user={};
            window.location.href="/";
        },
        setAuthenticate()
        {
             this.state.authenticated=false;
        }
    }
});