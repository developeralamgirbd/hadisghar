<template>
    <div class="flex">
        <div class="w-1/6 role_nav">
            <a @click="open" :class="isOpen ? 'bg-white' : ''" href="#">{{ title }}</a>
        </div>
        <div class="roles-tab-wrap w-5/6">
            <div v-if="isOpen" class="role_permission_wrap" >
                <slot></slot>
            </div>

        </div>
    </div>

</template>

<script>

export default {
    props:{
        title: String
    },
    data(){
        return{
            isOpen: false,
            isClose: true,
        }
    },

   methods: {
        open(){
           this.isOpen = !this.isOpen

        }
   }


}

</script>

<style>

.roles-tab-wrap table{
    width: 100%;
    border-collapse: separate;
    border-spacing: 10px 30px;
}

.roles-tab-wrap table thead tr th{
    text-align: right;
}
.roles-tab-wrap table thead tr th:first-child{
    text-align: left;
}

.roles-tab-wrap table tbody tr td{
    text-align: right;
}
.roles-tab-wrap table tbody tr td:first-child{
    text-align: left;
}
.role_nav {
    background: #eaeaea;
    border-bottom: 1px solid #d0cfcf;
}
.role_nav a {
    display: block;
    padding: 10px;
    transition: 0.3s
}
.role_nav a:hover {
    background: white;
    transition: 0.3s;
}
</style>
