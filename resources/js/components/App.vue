<template>
    <div :class="{'sidebar-mini': sidebarMiniClass}">
        <div>
            <div class="_1side_menu _1side_menu_bg" >
                <div class="_1side_menu_content">
                    <div class="_1side_menu_list">
                        <ul class="_1side_menu_list_ul">
                            <li>
                                <router-link :to="{name: 'home'}" custom v-slot="{ href, navigate, isActive, isExactActive }">
                                    <a :class="{ 'router-link-exact-active sidebar-link': isActive || isExactActive || subIsActive('/home') }" :href="href" @click="navigate">
                                        <Icon type="ios-home" size="24" />
                                        <div class="_1side_menu_list_ul_item_text">Home</div>
                                    </a>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--========== ADMIN SIDE MENU ========-->

            <!--========= HEADER ==========-->
            <div class="header">
                <div class="_2menu _box_shadow">

                    <div class="_2menu_logo _1side_menu_bg" :class="{'d-none': sidebarMiniClass}">
                        <ul class="open_button">
                            <li class="text-white text-center">Google Sheets</li>
                        </ul>
                    </div>

                    <ul class="open_button ">
                        <li>
                            <Icon type="ios-list" @click="this.sidebarMiniClass = !this.sidebarMiniClass" />
                        </li>
                    </ul>
                </div>

                <div class="_2menu _2menu_ceta">
                    <div class="_1side_menu_content">
                        <ul class="open_button">
                            <li class="text-center"><Icon type="ios-person" /> Admin</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--========= HEADER ==========-->

        </div>
        <div class="content pl-0 pr-0">
            <div class="container-fluid pl-0 pr-0">
                <router-view :key="$route.path"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sidebarMiniClass: false,
        }
    },
    methods: {
        subIsActive(input) {
            const paths = Array.isArray(input) ? input : [input]
            return paths.some(path => {

                let segment = this.$route.path.indexOf(path) === 0

                return segment // current path starts with this path string
            })
        },
    },
    async created(){},
}
</script>
