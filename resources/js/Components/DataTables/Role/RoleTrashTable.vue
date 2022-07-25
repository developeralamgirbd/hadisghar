<template>
    <div class="search flex justify-between items-center w-full mb-4 md:mb-0">
        <div class="">
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
            <th v-for="(column,name, index) in columns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                    <span>{{ name }}</span>
                    <i v-if= "sortDirection === 'asc' && name != 'Action'" class= "fa-solid fa-arrow-down-a-z cursor-pointer"> </i>
                    <i v-if="sortDirection === 'desc' && name != 'Action'" class="fa-solid fa-arrow-up-z-a cursor-pointer"></i>
                </div>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr v-if="rows.length > 0" v-for="(user, index) in rows" :key="user.id">
            <td>
                <div class="flex items-center gap-2">
                    <img class="h-8 w-8 rounded-full object-cover shadow-sm" :src="user.profile_photo_url" :alt="user.name">
                    <span>{{ user.name }}</span>
                </div>
            </td>
            <td>
                {{ user.email }}
            </td>
            <td>
                <span v-for="role in user.roles" :key="role.id">
                    {{ role.name.toLocaleUpperCase() }}
                </span>
            </td>
            <td>
                {{ user.post.length }}
            </td>
            <td>
                {{ moment(user.created_at).format('MMMM Do, YYYY')}}
            </td>
            <td>
                <div class="">
                    <span v-if="permissionArr.includes('delete user')">
                        <button type="button" class="bg-green-400 hover:bg-green-600 transition duration-300 rounded text-gray-50 px-2 py-1 mr-2" @click.prevent="restore(user.id, user.name)">Restore</button>
                    </span>

                    <span v-if="permissionArr.includes('delete user')">
                        <button type="button" class="bg-red-400 hover:bg-red-600 transition duration-300 rounded text-gray-50 px-2 py-1" @click.prevent="deleteSwal(user.id, user.name)">Permanently Delete</button>
                    </span>
                </div>
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
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import JetInput from '@/Jetstream/Input.vue';
import Moment from 'moment';
import Swal from "sweetalert2";
import * as $ from 'alga-js';
import _ from "lodash";
export default {
    name: "RoleTrashTable",
    props: {
        trashed: Object
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
            rawRows: this.$props.trashed,
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            currentPage: 1,
            allPages: 1,
            sortDirection: 'desc',
            rows: [],
            columns: {
                User: 'name',
                Email: 'email',
                Role: '',
                Post: '',
                Registered: 'created_at',
                Action: ''
            },

            isSearchNull: false,
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
                html: `<span class="text-red-600">Permanently Delete: </span> ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.user.permanently.destroy', id), {
                        preserveState: false,
                        preserveScroll: true
                    });
                    Swal.fire(
                        'Deleted!',
                        'Your post has been deleted.',
                        'success'
                    )
                }
            })
        },
        restore: function (id, name) {
            Swal.fire({
                title: 'Are you sure?',
                html: `<span class="text-red-600">Restore: </span> ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.get(route('admin.user.restore', id));
                    Swal.fire(
                        'Restored',
                        'Your post has been restore.',
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
