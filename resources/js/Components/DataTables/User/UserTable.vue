<template>
    <div class="search lg:flex lg:justify-between lg:items-center w-full mb-4 md:mb-0">
        <div class="mb-4">
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
    <div class="hidden md:block">
        <table class="w-full bg-gray-50 rounded table-auto" id="dataTable" ref="dataTable">
        <thead class="text-left">
        <tr>
            <th v-for="(column,name, index) in columns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                    <span>{{ name }}</span>
                    <fa icon="fa-arrow-down-a-z" v-if= "sortDirection === 'asc' && name != 'Action'" class= "cursor-pointer"/>
                    <fa icon="fa-arrow-up-z-a" v-if="sortDirection === 'desc' && name != 'Action'" class="cursor-pointer"/>
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
                {{ user.posts_count }}
            </td>
            <td>
                {{ moment(user.created_at).format('MMMM Do, YYYY')}}
            </td>
            <td>
                <div class="">
                    <span v-for="role in user.roles" :key="role.id" class="">
                        <span v-if="role.name.toLowerCase() !== 'admin' && permissionArr.includes('edit user')">
                           <Link :href="route('admin.user.edit', user.id)" class="mr-2 bg-green-400 hover:bg-green-600 transition text-gray-50 rounded py-1 px-[10px] text-[12px]">
                               <fa icon="fa-pen-to-square"/></Link>
                        </span>
                    </span>

                    <span v-for="role in user.roles" :key="role.id" class="">
                        <span v-if="role.name.toLowerCase() !== 'admin' && permissionArr.includes('delete user')">
                            <Link class="mr-2 bg-red-400 hover:bg-red-600 transition text-gray-50 rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(user.id)"><fa icon="fa-trash-can"/></Link>
                        </span>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    </div>

    <!--------------------
    Responsive Table
    ---------------------->
    <div class="block md:hidden">
        <table class="w-full bg-gray-50 rounded table-auto" id="responsiveDataTable" ref="responsiveDataTable">
            <thead class="text-left">
            <tr>
                <th v-for="(column,name, index) in responsiveColumns" :key="index" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                    <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                        <span>{{ name }}</span>
                        <fa icon="fa-arrow-down-a-z" v-if= "sortDirection === 'asc' && name != 'Action'" class= "cursor-pointer"> </fa>
                        <fa icon="fa-arrow-up-z-a" v-if="sortDirection === 'desc' && name != 'Action'" class="cursor-pointer"></fa>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="rows.length > 0" v-for="(user, index) in rows" :key="user.id">
                <td class="py-6">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <img class="h-8 w-8 rounded-full object-cover shadow-sm" :src="user.profile_photo_url" :alt="user.name">
                            <span>{{ user.name }}</span>
                        </div>
                        <button @click="showData(user.id)" type="button" class="flex justify-center items-center border border-green-400 p-2 rounded-full h-8 w-8 text-green-400 hover:bg-green-500 hover:text-white transition duration-300"><fa icon="fa-arrow-down"/></button>
                    </div>

                    <div class="mt-4 border-t border-green-400" :class="isOpen === user.id ? 'block' : 'hidden'">
                        <p class="mb-6 mt-4">Email: {{ user.email }}</p>
                        <p class="mb-6">Role:
                            <span v-for="role in user.roles" :key="role.id">
                                {{ role.name.toLocaleUpperCase() }}
                            </span>
                        </p>
                        <p class="mb-6">Post: {{ user.posts_count }}</p>
                        <p class="mb-6">Registered: {{ moment(user.created_at).format('MMMM Do, YYYY')}}</p>
                        <p class="mb-6">Action:
                            <span v-for="role in user.roles" :key="role.id" class="mr-2 lg:mr-0 text-lg lg:text-md">
                                <span v-if="role.name.toLowerCase() !== 'admin' && permissionArr.includes('edit user')">
                                   <Link :href="route('admin.user.edit', user.id)" class="mr-1 bg-green-400 hover:bg-green-600 transition text-gray-50 rounded py-1 px-[10px]">
                                       <fa icon="fa-pen-to-square"/></Link>
                                </span>
                            </span>

                            <span v-for="role in user.roles" :key="role.id" class="text-lg lg:text-md">
                                <span v-if="role.name.toLowerCase() !== 'admin' && permissionArr.includes('delete user')">
                                    <Link class="mr-2 bg-red-400 hover:bg-red-600 transition text-gray-50 rounded py-1 px-[10px]" @click.prevent="deleteSwal(user.id)"><fa icon="fa-trash-can"/></Link>
                                </span>
                            </span>
                        </p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
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
    name: "UserTable",
    props: {
        users: Object,
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
            rawRows: this.$props.users,
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            currentPage: 1,
            allPages: 1,
            sortDirection: 'desc',
            rows: [],
            columns: {
                User: 'name',
                Email: 'email',
                Role: 'roles',
                Post: '',
                Registered: 'created_at',
                Action: ''
            },
            responsiveColumns: {
                User: 'name',
            },

            isSearchNull: false,
            isOpen: '',
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
        console.log(this.$props.users);
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
        showData(id){
            this.isOpen = id;
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

        deleteSwal: function (id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.user.destroy', id), {
                        preserveState: false,
                        preserveScroll: true,
                    });
                    Swal.fire(
                        'Deleted!',
                        'Your post has been deleted.',
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
