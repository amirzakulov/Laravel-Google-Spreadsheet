import {defineStore} from 'pinia'

export const useOrdersStore = defineStore("ordersStore", {
    state: () => {
        return {
            deleteModal:false,
            orders:    [],
            order:     {first_name:'', last_name:'', phone:'', description:''},
            rowIndex:   0,
            access_token: '',

        }
    },

    getters: {},

    actions: {
        modalVisibility(status){
            if(!status) {
                this.order  = {}
            }
        },
    }
})
