<template>
    <OrdersHeader />

    <div class="_1adminOverveiw_table_recent _border_radious mt-2 mb-2">
        <Row :gutter="8" class="mr-4">
            <Col v-if="!ordersStore.access_token" span="24" class="text-right">
                <Text type="danger" class="mr-2">У вас нет доступа для создание онлайн заявки. Для получения доступа нажмите</Text>

                <Button type="primary" @click="getAuth()" :disabled="isChecking" :loading="isChecking">{{isChecking ? 'Подождите...':'Получить доступ'}}</Button></Col>
        </Row>


        <Form label-position="top" class="paymentSearchBox mt-2">
            <Row :gutter="8">
                <Col span="4">
                    <FormItem label="Имя">
                        <Input v-model="ordersStore.order.first_name" clearable placeholder="Имя..." />
                        <Text :class="{ 'd-none': !first_name_err}" type="danger"> Обязательное поле!</Text>
                    </FormItem>
                </Col>
                <Col span="4">
                    <FormItem label="Фамилия">
                        <Input v-model="ordersStore.order.last_name" clearable placeholder="Фамилия..." />
                        <Text :class="{ 'd-none': !last_name_err}" type="danger"> Обязательное поле!</Text>
                    </FormItem>
                </Col>
                <Col span="4">
                    <FormItem label="Телефон">
                        <Input v-model="ordersStore.order.phone" clearable placeholder="Телефон..." />
                        <Text :class="{ 'd-none': !phone_err}" type="danger"> Обязательное поле!</Text>
                    </FormItem>
                </Col>
                <Col span="4">
                    <FormItem label="Описание">
                        <Input v-model="ordersStore.order.description" clearable placeholder="текст заявк..." />
                    </FormItem>
                </Col>
                <Col span="4">
                    <FormItem label="&nbsp;">
                        <Button type="primary" @click="addOrder" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Создать...':'Создать'}}</Button>
                    </FormItem>
                </Col>
            </Row>
        </Form>
    </div>

    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious pr-1">
        <Table class="myCustomTable"
               size="small" :columns="columns"
               :data="filteredPayments" :loading="loading"
        >
            <template #url="{row}">

                <a :href="'https://docs.google.com/spreadsheets/d/'+row.spreadsheet_id" target="_blank">Ссылка на документ</a>

            </template>
            <template #created_at="{row}">
                {{(myDateFormat(row.created_at, "mm.dd.yyyy hh:mm"))}}
            </template>
        </Table>
    </div>

</template>

<script>
    import OrdersHeader from "./parts/header"
    import {useOrdersStore} from "../../stores/OrdersStore";
    import {resolveComponent} from "vue";

    export default {
    name: "home",
    setup(){
        const ordersStore     = useOrdersStore()
        return { ordersStore }
    },
    components: {
        OrdersHeader: OrdersHeader,
    },
    computed: {
        filteredPayments(){
            this.ordersTmp = this.ordersStore.orders

            if (this.keywordName !== '' && this.keywordName) {
                this.ordersTmp = this.ordersTmp.filter((order) => {
                    return order.last_name.toLowerCase().includes(this.keywordName.toLowerCase())
                })
            }

            if (this.keywordPhone !== '' && this.keywordPhone) {
                this.ordersTmp = this.ordersTmp.filter((order) => {
                    return order.phone.toLowerCase().includes(this.keywordPhone.toLowerCase())

                })
            }

            return this.ordersTmp
        },
    },
    data() {
        return {
            isAdding: false,
            isChecking: false,
            keywordName: '',
            keywordPhone: '',
            ordersTmp: [],

            loading: true,
            columns: [
                {
                    title: "Имя",
                    key: 'first_name',
                    sortable: true,
                }, {
                    title: "Фамилия",
                    key: 'last_name',
                    sortable: true,
                }, {
                    title: "Телефон",
                    key: 'phone',
                }, {
                    title: "Описание",
                    key: 'description',
                }, {
                    title: "URL",
                    slot: 'url'
                }, {
                    title: "Сана",
                    slot: 'created_at'
                },

            ],

            first_name_err: false,
            last_name_err: false,
            phone_err: false,
            is_invalid: false,
        }
    },
    methods: {
        async addOrder(){

            this.isAdding = true
            if(this.ordersStore.order.first_name == '') {
                this.is_invalid = true
                this.first_name_err = true
            } else {
                this.first_name_err = false
            }

            if(this.ordersStore.order.last_name == '') {
                this.is_invalid = true
                this.last_name_err = true
            } else {
                this.last_name_err = false
            }

            if(this.ordersStore.order.phone == '') {
                this.is_invalid = true
                this.phone_err = true
            } else {
                this.phone_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                this.isAdding = false
                return this.err('Поля обязательны для заполнения!')
            }

            const res = await this.callApi("post", "/app/add_order", this.ordersStore.order)
            console.log("Access Token", res)
            this.isAdding = false
            if(res.status == 200) {
                if(res.data == false) {
                    this.err('Вы не авторизован!')
                } else {
                    this.ordersStore.orders.unshift(res.data);
                    this.ordersStore.order = {first_name:'', last_name:'', phone:'', description:''}
                    this.s('Заявка создано успешно!')

                    const accessToken = this.callApi("get", "/app/get_access_token")
                    this.ordersStore.access_token = accessToken.data.access_token
                }

            } else if(res.status == 500){
                this.err(res.data.message)
            } else {
                if(res.status == 422) {
                    if(res.data.errors.first_name) {
                        this.err(res.data.errors.first_name[0])
                    }

                    if(res.data.errors.last_name) {
                        this.err(res.data.errors.last_name[0])
                    }

                    if(res.data.errors.phone) {
                        this.err(res.data.errors.phone[0])
                    }
                } else {
                    this.swr()
                }
            }

        },

        async getAuth(){

            const auth_url = await this.callApi("get", "/app/generate_google_oauth_url")
            window.open(auth_url.data, "_blank")

        }

    },
    async created(){
        const [orders, accessToken] = await Promise.all([
            this.callApi("get", "/app/get_orders"),
            this.callApi("get", "/app/get_access_token"),
        ])

        this.ordersStore.orders = orders.data
        this.loading = false

        this.ordersStore.access_token = accessToken.data.access_token

    }
}
</script>

