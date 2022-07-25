<template>
    <form @submit.prevent="submit(roleAndPermissions[0].id)">
        <input type="text" v-model="form.role_name" name="role_name" class="border rounded border-green-200 focus:ring-green-400 focus:border-green-400 w-full md:w-1/2" :class="{'border-red-500' : errors.role_name}" placeholder="Enter new role name">
        <p v-if="errors.role_name" class="text-red-500">{{ errors.role_name }}</p>

        <div class="w-full lg:w-5/6 md:flex gap-2 mt-6">
            <div class="md:w-5/6">
                <div class="search md:flex md:justify-between md:items-center w-full mb-4 md:mb-0">
                    <div class="mb-4 md:mb-0">
                        <span class="mr-1 text-green-500">Show</span>
                        <select v-model="currentEntries" class="border-gray-300 py-1 pr-6 focus:ring-green-400 focus:outline-none focus:border-green-400" @change="paginateEntries()">
                            <option v-for="(show, i) in showEntries" :key="show" :value="show">{{ show }}</option>
                        </select>
                    </div>
                    <div class="">
                        <JetInput
                            id="email"
                            type="text"
                            name="search"
                            @input="onSearch"
                            class="mb-2 block w-full"
                            placeholder="Search"
                            autofocus
                        />
                    </div>
                </div>

                <table class="w-full bg-gray-50 rounded table-auto" id="dataTable" ref="dataTable">
                    <thead class="text-left">
                    <tr>
                        <th v-for="(column,name, index) in columns" :key="index" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                            <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                                <span>{{ name }}</span>
                                <fa icon="fa-arrow-down-a-z" v-if= "sortDirection === 'asc' && name != 'Action'" class= "cursor-pointer"> </fa>
                                <fa icon="fa-arrow-up-z-a" v-if="sortDirection === 'desc' && name != 'Action'" class="cursor-pointer"></fa>
                            </div>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-if="rows.length > 0" v-for="(permission, index) in rows" :key="index">
                        <td>
                            <label :for="`permission${permission.id}`">{{permission.name.toUpperCase()}}</label>
                        </td>
                        <td>
                            <input type="checkbox" v-model="form.permissionsArr"  :value="permission.name" :id="`permission${permission.id}`" :name="`permissionSelect${permission.id}`" class="cursor-pointer">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p class="text-center text-lg mt-4" v-if="isSearchNull">Data not found</p>

                <!-- Pagination-->
                <div class="md:flex md:justify-between md:items-center mt-4 mb-20">
                    <div class="">Show {{ showInfo.from }} to {{ showInfo.to }} of {{ showInfo.of }} entries</div>
                    <div class="">
                        <div class="block md:hidden">
                            <div class="gap-1 items-center justify-between flex">
                                <div class="">
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === 1}" type="button" @click.prevent="currentPage = 1, paginateEntries()">First</button>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden' : currentPage === 1}" type="button" @click.prevent="(currentPage < 1) ? currentPage = 1 : currentPage -= 1, paginateEntries()">Prev</button>
                                </div>
                                <div class="">
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="(currentPage > allPages) ? currentPage = 1 : currentPage += 1, paginateEntries()">Next</button>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="currentPage = allPages, paginateEntries()">Last</button>
                                </div>
                            </div>
                        </div>

                        <div class="hidden md:block">
                            <ul class="flex gap-1 items-center">
                                <li>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === 1}" type="button" @click.prevent="currentPage = 1, paginateEntries()">First</button>
                                </li>
                                <li>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden' : currentPage === 1}" type="button" @click.prevent="(currentPage < 1) ? currentPage = 1 : currentPage -= 1, paginateEntries()">Prev</button>
                                </li>

                                <li v-for="pageNum in showPagination" :key="pageNum"
                                    class="border border-white hover:text-blue-500 transition rounded"
                                    :class="pageNum === currentPage ? 'bg-gray-300 border-white' : 'text-gray-900'"
                                    v-if="currentEntries < showInfo.of">
                                    <button type="button" @click.prevent="paginateEvent(pageNum)" class="block py-1 px-4" :disabled="pageNum === currentPage" v-if="currentEntries < showInfo.of">{{ pageNum }}</button>
                                </li>

                                <li>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="(currentPage > allPages) ? currentPage = 1 : currentPage += 1, paginateEntries()">Next</button>
                                </li>
                                <li>
                                    <button class="border border-white hover:text-blue-500 transition py-1 px-4 rounded" :class="{'hidden': currentPage === allPages || rows.length < 1 || showInfo.of === currentEntries}" type="button" @click.prevent="currentPage = allPages, paginateEntries()">Last</button>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
            <div class="md:w-1/6 mt-6">
                <div class="bg-white rounded shadow p-2">
                    <ul class="text-sm">
                        <li class="mb-6"><fa icon="fa-check" class="mr-2"/> Granted: {{ form.permissionsArr.length }}</li>
                        <li class="mb-6"><i icon="fa-xmark" class="mr-2"/> Denied: {{ permissions.length - form.permissionsArr.length}}</li>
                        <li class="text-right border-t mt-2 "><button type="submit" class="bg-green-500 px-2 py-1 mt-2 rounded text-white">Update</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</template>
<script>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import JetInput from '@/Jetstream/Input.vue';
import Moment from 'moment';
import Swal from "sweetalert2";
import * as $ from 'alga-js';
import _ from "lodash";

export default {
    name: "EditTable",
    props: {
        permissions: Object,
        roleAndPermissions: Object,
        errors: Object,
        // trashed: Number
    },
    components: {
      Link, JetInput
    },
    data(){
        return {
            domainName: window.location.origin+'/',
            moment: Moment,
            active_user_id: null,
            permissionArr: [],
            rawRows: this.$props.permissions,
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            currentPage: 1,
            allPages: 1,
            sortDirection: 'desc',
            rows: [],
            columns: {
                Permission: 'name',
                Grant: '',
            },
            isSearchNull: false,

            active: false,
            form: useForm({
                role_name: '',
                permissionsArr: []
            })
        }
    },

    created(){
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
        this.rows = this.rawRows;
        this.rows = $.array.paginate(this.rawRows, this.currentPage, this.currentEntries);
        this.allPages = $.array.pages(this.rawRows, this.currentEntries);

        this.active_user_id = this.$page.props.active_user_id;

        this.form.role_name = this.$props.roleAndPermissions[0]['name'];

        let permissions = this.$props.roleAndPermissions[0].permissions;

        permissions.forEach(item =>
            this.form.permissionsArr.push(item.name)
        )
    },

    computed: {
        showInfo(){
            return $.array.pageInfo(this.rawRows, this.currentPage, this.currentEntries);
        },
        showPagination(){
            return $.array.pagination(this.allPages, this.currentPage, 3);
        }
    },

    methods: {
        submit(id){
            this.form.put(route('admin.role.update', id), {
                onSuccess: () => {
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    }).fire({
                        icon: 'success',
                        title: 'Role updated successfully'
                    })
                }
            })
        },
        sortRecords(column){
            this.sortDirection === 'desc' ? this.sortDirection = 'asc' : this.sortDirection === 'asc' ? this.sortDirection = 'desc' : '';
            this.rows = $.array.sortBy(this.rows, column, this.sortDirection)
        },

        onSearch: _.debounce(function (e){
            if (e.target.value.length === 0){
                this.currentEntries = 10;
                this.paginateEntries();
            }else {
                this.currentEntries = this.showInfo.of;
                this.paginateEntries();
                this.rows = $.array.search(this.rawRows, e.target.value)
            }
        }, 400),
        paginateEntries(){
            this.rows = $.array.paginate(this.rawRows, this.currentPage, this.currentEntries);
        },

        paginateEvent(page){
            this.currentPage = page;
            this.paginateEntries();
        },

        deleteSwal: function (id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete Role: ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.role.destroy', id), {
                        preserveState: false,
                        preserveScroll: true,
                    });
                    Swal.fire(
                        'Deleted!',
                        'Role has been deleted.',
                        'success'
                    )
                }
            })
        },
    },

    watch: {
        currentEntries: {
            handler(){
                this.allPages = $.array.pages(this.rawRows, this.currentEntries);
            }
        }
    },

}
</script>

<style>

.email:hover .sort{
    display: block!important;
}
</style>
