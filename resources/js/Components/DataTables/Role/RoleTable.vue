<template>
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
        <tr v-if="rows.length > 0" v-for="(role, index) in rows" :key="index">
            <td>
                {{ role.name.toLocaleUpperCase() }}
            </td>
            <td>
                {{role.permissions.length}}
            </td>
            <td>
                {{ totalPermissions - role.permissions.length }}
            </td>
            <td>
                <div class="">
                        <span v-if="role.name.toLowerCase() !== 'user' && permissionArr.includes('edit role') && role.name.toLowerCase() !== 'admin'">
                           <Link :href="route('admin.role.edit', role.id)" class="mr-2 bg-green-400 hover:bg-green-600 transition text-gray-50 rounded py-1 px-[10px] text-[12px]">
                               <fa icon="fa-pen-to-square"/></Link>
                        </span>
                        <span v-if="permissionArr.includes('delete role') && role.name.toLowerCase() !== 'admin'">
                            <button type="button" class="mr-2 bg-red-400 hover:bg-red-600 transition text-gray-50 rounded py-1 px-[10px] text-[12px]" @click.prevent="deleteSwal(role.id, role.name)">
                                <fa icon="fa-trash-can"/>
                            </button>
                        </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    </div>

    <!---------------------------
    ! Responsive Table
    ----------------------------->
    <div class="block md:hidden">
        <table class="w-full bg-gray-50 rounded table-auto" id="responsiveDataTable" ref="responsiveDataTable">
            <thead class="text-left">
            <tr>
                <th v-for="(column,name, index) in responsiveColumns" :key="index" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                    <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                        <span>{{ name }}</span>
                        <fa icon="fa-arrow-down-a-z" v-if= "sortDirection === 'asc' && name != 'Action'" class= "cursor-pointer"/>
                        <fa icon="fa-arrow-up-z-a" v-if="sortDirection === 'desc' && name != 'Action'" class="cursor-pointer"/>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="rows.length > 0" v-for="(role, index) in rows" :key="index">
                <td>
                    <div class="flex justify-between">
                        {{ role.name.toLocaleUpperCase() }}
                        <button @click="showData(role.id)" type="button" class="flex justify-center items-center border border-green-400 p-2 rounded-full h-8 w-8 text-green-400 hover:bg-green-500 hover:text-white transition duration-300"><fa icon="fa-arrow-down"/></button>
                    </div>

                    <div class="mt-4 border-t border-green-400" :class="isOpen === role.id ? 'block' : 'hidden'">
                        <p class="mb-6 mt-4">Granted:  {{role.permissions.length}}</p>
                        <p class="mb-6">Denied: {{ totalPermissions - role.permissions.length }}</p>

                        <p class="mb-6">Action:
                            <span v-if="role.name.toLowerCase() !== 'user' && permissionArr.includes('edit role') && role.name.toLowerCase() !== 'admin'" class="text-lg lg:text-md mr-2">
                               <Link :href="route('admin.role.edit', role.id)" class="bg-green-400 hover:bg-green-600 transition text-gray-50 rounded px-2 py-1">
                                   <fa icon="fa-pen-to-square"/></Link>
                            </span>

                            <span v-if="permissionArr.includes('delete role') && role.name.toLowerCase() !== 'admin'" class="text-lg lg:text-md">
                                <button type="button" class="bg-red-400 hover:bg-red-600 transition text-gray-50 rounded px-2" @click.prevent="deleteSwal(role.id, role.name)">
                                    <fa icon="fa-trash-can"/>
                                </button>
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
    name: "RoleTable",
    props: {
        roles: Object,
        totalPermissions: Number,
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
            rawRows: this.$props.roles,
            showEntries: [5, 10, 15, 25, 50, 75, 100 ],
            currentEntries: 10,
            currentPage: 1,
            allPages: 1,
            sortDirection: 'desc',
            rows: [],
            columns: {
                Role: 'name',
                Granted: 'email',
                Denied: 'roles',
                Action: '',
            },
            responsiveColumns: {
                Role: 'name',
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
