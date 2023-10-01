<template>
    <div>asdkjflsajdfljas</div>
</template>

<script>
import {useOrdersStore} from "../../stores/OrdersStore";
export default {
    name: "Google Code",
    setup(){
        const ordersStore     = useOrdersStore()
        return { ordersStore }
    },
    data() {
        return {}
    },
    methods: {},
    async created(){
        let code = this.$route.query.code

        const access_token = await this.callApi('post', '/app/get_google_oauth_code', {code:code});

        if(access_token.data) {
            this.ordersStore.access_token = access_token.data
            window.close();
        }
    }
}
</script>

