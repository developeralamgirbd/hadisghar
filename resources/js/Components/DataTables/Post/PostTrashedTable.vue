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
    <div class="hidden lg:block">
        <table class="w-full bg-gray-50 rounded table-auto" id="dataTable" ref="dataTable">
        <thead class="text-center">
        <tr>
            <th v-for="(column,name, index) in columns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                    <span>{{ name }}</span>
                    <fa v-if= "sortDirection === 'asc' && name != 'Action'" icon="fa-arrow-down-a-z" class= "cursor-pointer"> </fa>
                    <fa v-if="sortDirection === 'desc' && name != 'Action'" icon="fa-arrow-up-z-a" class="cursor-pointer"></fa>
                </div>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr v-if="rows.length > 0" v-for="(post, index) in rows" :key="post.id">
            <td>
                {{ post.title ? post.title.length > 50 ? post.title.substring(0, 50) +'...' : post.title : '' }}
            </td>
            <td>
                {{ post.category ? post.category.category_name : '' }}
            </td>
            <td>
                {{ post.user ? post.user.name : '' }}
            </td>
            <td>
                {{ moment(post.created_at).format('MMMM Do, YYYY') }}
            </td>
            <td>
                <div class="">
                     <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')">
                           <button @click="restore(post.id, post.title)" class="mr-2 bg-green-400 hover:bg-green-600 px-2 py-1 transition duration-300 rounded text-gray-50">Restore</button>
                     </span>
                    <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id">
                           <button @click="restore(post.id, post.title)" class="mr-2 bg-green-400 hover:bg-green-600 px-2 py-1 transition duration-300 rounded text-gray-50">Restore</button>
                     </span>

                    <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')">
                            <button type="button" class="bg-red-400 hover:bg-red-600 transition duration-300 rounded text-gray-50 px-2 py-1" @click.prevent="deleteSwal(post.id, post.title)">Permanently Delete</button>
                    </span>

                    <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id">
                            <button type="button" class="bg-red-400 hover:bg-red-600 transition duration-300 rounded text-gray-50 px-2 py-1" @click.prevent="deleteSwal(post.id, post.title)">Permanently Delete</button>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    </div>

    <!----------------------
      ! Responsive Table
      ------------------------>
    <div class="block lg:hidden">
        <table class="w-full bg-gray-50 rounded table-auto" id="responsiveDataTable" ref="responsiveDataTable">
            <thead class="text-left">
            <tr>
                <th v-for="(column,name, index) in responsiveColumns" :key="column.id" class="text-gray-900 font-bold" @click="sortRecords(column)" >
                    <div class="flex items-center justify-between hover:cursor-pointer cursor-pointer email w-full">
                        <span>{{ name }}</span>
                        <fa v-if= "sortDirection === 'asc' && name !== 'Action'" icon="fa-arrow-down-a-z" class="cursor-pointer"> </fa>
                        <fa v-if="sortDirection === 'desc' && name !== 'Action'" icon="fa-arrow-up-z-a" class="cursor-pointer"></fa>
                    </div>
                </th>
            </tr>
            </thead>

            <tbody>


            <tr v-if="rows.length > 0" v-for="(post, index) in rows" :key="index">
                <td class="py-4">
                    <div class="flex justify-between">
                        {{ post.title ? post.title.length > 70 ? post.title.substring(0, 70) +'...' : post.title : '' }}
                        <button @click="showData(post.id)" type="button" class="flex justify-center items-center border border-green-400 p-2 rounded-full h-8 w-8 text-green-400 hover:bg-green-500 hover:text-white transition duration-300">
                            <fa icon="fa-arrow-down"></fa></button>
                    </div>

                    <div class="mt-4 border-t border-green-400" :class="isOpen === post.id ? 'block' : 'hidden'">
                        <p class="mb-6 ">Category: {{ post.category ? post.category.category_name : '' }}</p>
                        <p class="mb-6">Author: {{ post.user ? post.user.name : '' }}</p>
                        <p class="mb-6">Author: {{ post.user ? post.user.name : '' }}</p>
                        <p class="mb-6">Status:
                            <span :class="post.status === 'published' ? 'text-green-600' : '' || post.status === 'pending' ? 'text-red-600' : '' || post.status === 'draft' ? 'text-gray-600' : ''">
                            {{ post.status }}
                        </span>
                        </p>
                        <p class="mb-6">Date: {{ moment(post.created_at).format('MMMM Do, YYYY')}}</p>
                        <p class="mb-6">Action:
                            <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')">
                           <button @click="restore(post.id, post.title)" class="mr-2 bg-green-400 hover:bg-green-600 px-2 py-1 transition duration-300 rounded text-gray-50">Restore</button>
                     </span>
                            <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id">
                           <button @click="restore(post.id, post.title)" class="mr-2 bg-green-400 hover:bg-green-600 px-2 py-1 transition duration-300 rounded text-gray-50">Restore</button>
                     </span>

                            <span v-if="permissionArr.includes('delete post') && permissionArr.includes('delete others post')">
                            <button type="button" class="bg-red-400 hover:bg-red-600 transition duration-300 rounded text-gray-50 px-2 py-1" @click.prevent="deleteSwal(post.id, post.title)">Permanently Delete</button>
                    </span>

                            <span v-if="permissionArr.includes('delete post') && !permissionArr.includes('delete others post') && active_user_id === post.user.id">
                            <button type="button" class="bg-red-400 hover:bg-red-600 transition duration-300 rounded text-gray-50 px-2 py-1" @click.prevent="deleteSwal(post.id, post.title)">Permanently Delete</button>
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
    name: "PostTrashedTable",
    props: {
        trashed: Object,
    },
    components: {
      Link, JetInput
    },
    data(){
        return {
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
                Title: 'title',
                Category: 'category_id',
                Author: 'user_id',
                Date: 'deleted_at',
                Action: ''
            },
            responsiveColumns: {
                Title: 'title',
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

        deleteSwal: function (id, title) {
            Swal.fire({
                title: 'Are you sure?',
                html: `<span class="text-red-600">Permanently Delete: </span> ${title}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.post.permanently.destroy', id), {
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
        restore: function (id, title) {
            Swal.fire({
                title: 'Are you sure?',
                html: `<span class="text-red-600">Restore: </span> ${title}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.get(route('admin.post.restore', id));
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
</style>
